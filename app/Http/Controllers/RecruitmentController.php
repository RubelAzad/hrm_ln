<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBackgroundVerificationRequest;
use App\Http\Requests\StoreCandidateCommunicationRequest;
use App\Http\Requests\StoreCandidateRequest;
use App\Http\Requests\StoreInterviewRequest;
use App\Http\Requests\StoreJobPostingRequest;
use App\Http\Requests\StoreOfferLetterRequest;
use App\Http\Requests\StorePipelineStageRequest;
use App\Http\Requests\UpdateBackgroundVerificationRequest;
use App\Http\Requests\UpdateCandidateRequest;
use App\Http\Requests\UpdateInterviewRequest;
use App\Http\Requests\UpdateJobPostingRequest;
use App\Models\BackgroundVerification;
use App\Models\Candidate;
use App\Models\CandidateCommunication;
use App\Models\Interview;
use App\Models\JobPosting;
use App\Models\OfferLetter;
use App\Models\PipelineStage;
use App\Models\TalentPool;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RecruitmentController extends Controller
{
    public function dashboard(): View
    {
        $jobCount = JobPosting::count();
        $activeJobs = JobPosting::where('status', 'published')->count();
        $candidateCount = Candidate::count();
        $activeCandidates = Candidate::where('status', 'active')->count();
        $upcomingInterviews = Interview::where('status', 'scheduled')
            ->where('scheduled_at', '>=', now())
            ->with(['candidate', 'jobPosting'])
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();
        $pipelineStats = PipelineStage::selectRaw('stage, count(*) as total')
            ->groupBy('stage')
            ->pluck('total', 'stage');

        return view('recruitment.dashboard', compact(
            'jobCount', 'activeJobs', 'candidateCount', 'activeCandidates',
            'upcomingInterviews', 'pipelineStats'
        ));
    }

    public function index(Request $request): View
    {
        $query = Candidate::with(['talentPool', 'pipelineStages']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('current_company', 'like', "%{$search}%");
            });
        }

        if ($request->filled('stage')) {
            $query->whereHas('pipelineStages', fn($q) => $q->where('stage', $request->stage));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $candidates = $query->latest()->paginate(15);
        $jobs = JobPosting::where('status', 'published')->get();

        return view('recruitment.candidates.index', compact('candidates', 'jobs'));
    }

    public function create(): View
    {
        $jobs = JobPosting::where('status', 'published')->get();
        $pools = TalentPool::where('status', 'active')->get();

        return view('recruitment.candidates.create', compact('jobs', 'pools'));
    }

    public function store(StoreCandidateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['skills'] = $request->filled('skills')
            ? array_map('trim', explode(',', $request->skills))
            : null;

        if ($request->hasFile('resume')) {
            $validated['resume_path'] = $request->file('resume')
                ->store('resumes', 'public');
        }

        $candidate = Candidate::create($validated);

        if ($request->filled('job_posting_id')) {
            $candidate->pipelineStages()->create([
                'job_posting_id' => $request->job_posting_id,
                'stage' => 'applied',
                'stage_order' => 1,
                'stage_changed_at' => now(),
                'created_by' => $request->user()->id,
            ]);
        }

        return redirect()->route('recruitment.candidates.show', $candidate)
            ->with('success', 'Candidate added successfully.');
    }

    public function show(Candidate $candidate): View
    {
        $candidate->load([
            'talentPool',
            'pipelineStages.jobPosting',
            'pipelineStages.createdBy',
            'interviews.jobPosting',
            'interviews.createdBy',
            'offerLetters.jobPosting',
            'offerLetters.createdBy',
            'verifications.initiatedBy',
            'communications.jobPosting',
            'communications.sentBy',
        ]);

        $jobs = JobPosting::where('status', 'published')->get();
        $pools = TalentPool::where('status', 'active')->get();

        return view('recruitment.candidates.show', compact('candidate', 'jobs', 'pools'));
    }

    public function edit(Candidate $candidate): View
    {
        $jobs = JobPosting::where('status', 'published')->get();
        $pools = TalentPool::where('status', 'active')->get();

        return view('recruitment.candidates.edit', compact('candidate', 'jobs', 'pools'));
    }

    public function update(UpdateCandidateRequest $request, Candidate $candidate): RedirectResponse
    {
        $validated = $request->validated();
        $validated['skills'] = $request->filled('skills')
            ? array_map('trim', explode(',', $request->skills))
            : null;

        if ($request->hasFile('resume')) {
            if ($candidate->resume_path) {
                Storage::disk('public')->delete($candidate->resume_path);
            }
            $validated['resume_path'] = $request->file('resume')
                ->store('resumes', 'public');
        }

        $candidate->update($validated);

        return redirect()->route('recruitment.candidates.show', $candidate)
            ->with('success', 'Candidate updated successfully.');
    }

    public function destroy(Candidate $candidate): RedirectResponse
    {
        if ($candidate->resume_path) {
            Storage::disk('public')->delete($candidate->resume_path);
        }

        $candidate->delete();

        return redirect()->route('recruitment.candidates.index')
            ->with('success', 'Candidate deleted successfully.');
    }

    public function advanceStage(StorePipelineStageRequest $request, Candidate $candidate): RedirectResponse
    {
        $currentMax = $candidate->pipelineStages()->max('stage_order') ?? 0;

        $candidate->pipelineStages()->create([
            'job_posting_id' => $request->job_posting_id,
            'stage' => $request->stage,
            'stage_order' => $currentMax + 1,
            'notes' => $request->notes,
            'stage_changed_at' => now(),
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('recruitment.candidates.show', $candidate)
            ->with('success', 'Candidate moved to ' . ucfirst($request->stage) . ' stage.');
    }

    public function jobs(Request $request): View
    {
        $query = JobPosting::withCount('pipelineStages');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $jobs = $query->latest()->paginate(15);
        $departments = JobPosting::distinct('department')->pluck('department')->filter();

        return view('recruitment.jobs.index', compact('jobs', 'departments'));
    }

    public function createJob(): View
    {
        return view('recruitment.jobs.create');
    }

    public function storeJob(StoreJobPostingRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['created_by'] = $request->user()->id;

        if (($validated['status'] ?? '') === 'published') {
            $validated['posted_at'] = now();
        }

        JobPosting::create($validated);

        return redirect()->route('recruitment.jobs.index')
            ->with('success', 'Job posting created successfully.');
    }

    public function showJob(JobPosting $job): View
    {
        $job->load(['pipelineStages.candidate', 'createdBy']);

        $candidates = Candidate::whereHas('pipelineStages', fn($q) => $q->where('job_posting_id', $job->id))
            ->with(['pipelineStages' => fn($q) => $q->where('job_posting_id', $job->id)->latest('stage_order')])
            ->get();

        return view('recruitment.jobs.show', compact('job', 'candidates'));
    }

    public function editJob(JobPosting $job): View
    {
        return view('recruitment.jobs.edit', compact('job'));
    }

    public function updateJob(UpdateJobPostingRequest $request, JobPosting $job): RedirectResponse
    {
        $validated = $request->validated();

        if (($validated['status'] ?? '') === 'published' && $job->status !== 'published') {
            $validated['posted_at'] = now();
        }

        $job->update($validated);

        return redirect()->route('recruitment.jobs.index')
            ->with('success', 'Job posting updated successfully.');
    }

    public function destroyJob(JobPosting $job): RedirectResponse
    {
        $job->delete();

        return redirect()->route('recruitment.jobs.index')
            ->with('success', 'Job posting deleted successfully.');
    }

    public function interviews(Request $request): View
    {
        $query = Interview::with(['candidate', 'jobPosting', 'createdBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->where('scheduled_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->where('scheduled_at', '<=', $request->to . ' 23:59:59');
        }

        $interviews = $query->orderBy('scheduled_at')->paginate(15);

        return view('recruitment.interviews.index', compact('interviews'));
    }

    public function storeInterview(StoreInterviewRequest $request, Candidate $candidate): RedirectResponse
    {
        $candidate->interviews()->create(array_merge(
            $request->validated(),
            ['created_by' => $request->user()->id]
        ));

        return redirect()->route('recruitment.candidates.show', $candidate)
            ->with('success', 'Interview scheduled successfully.');
    }

    public function updateInterview(UpdateInterviewRequest $request, Interview $interview): RedirectResponse
    {
        $interview->update($request->validated());

        return redirect()->route('recruitment.candidates.show', $interview->candidate_id)
            ->with('success', 'Interview updated successfully.');
    }

    public function storeOffer(StoreOfferLetterRequest $request, Candidate $candidate): RedirectResponse
    {
        $candidate->offerLetters()->create(array_merge(
            $request->validated(),
            ['created_by' => $request->user()->id]
        ));

        return redirect()->route('recruitment.candidates.show', $candidate)
            ->with('success', 'Offer letter created successfully.');
    }

    public function storeVerification(StoreBackgroundVerificationRequest $request, Candidate $candidate): RedirectResponse
    {
        $candidate->verifications()->create(array_merge(
            $request->validated(),
            ['initiated_by' => $request->user()->id, 'initiated_at' => now()]
        ));

        return redirect()->route('recruitment.candidates.show', $candidate)
            ->with('success', 'Background verification initiated.');
    }

    public function updateVerification(UpdateBackgroundVerificationRequest $request, BackgroundVerification $verification): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('report')) {
            if ($verification->report_path) {
                Storage::disk('public')->delete($verification->report_path);
            }
            $validated['report_path'] = $request->file('report')
                ->store('verification-reports', 'public');
        }

        $verification->update($validated);

        return redirect()->route('recruitment.candidates.show', $verification->candidate_id)
            ->with('success', 'Verification updated successfully.');
    }

    public function storeCommunication(StoreCandidateCommunicationRequest $request, Candidate $candidate): RedirectResponse
    {
        $candidate->communications()->create(array_merge(
            $request->validated(),
            ['sent_by' => $request->user()->id, 'sent_at' => now()]
        ));

        return redirect()->route('recruitment.candidates.show', $candidate)
            ->with('success', 'Communication logged successfully.');
    }

    public function resumeDownload(Candidate $candidate)
    {
        if (!$candidate->resume_path) {
            return redirect()->back()->with('error', 'No resume found.');
        }

        return Storage::disk('public')->download($candidate->resume_path);
    }

    public function offerDownload(OfferLetter $offerLetter)
    {
        if (!$offerLetter->offer_letter_path) {
            return redirect()->back()->with('error', 'No offer letter file found.');
        }

        return Storage::disk('public')->download($offerLetter->offer_letter_path);
    }

    public function talentPools(Request $request): View
    {
        $query = TalentPool::withCount('candidates');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $pools = $query->latest()->paginate(15);

        return view('recruitment.talent-pools.index', compact('pools'));
    }

    public function storeTalentPool(\App\Http\Requests\StoreTalentPoolRequest $request): RedirectResponse
    {
        TalentPool::create($request->validated());

        return redirect()->route('recruitment.talent-pools.index')
            ->with('success', 'Talent pool created successfully.');
    }

    public function updateTalentPool(\App\Http\Requests\UpdateTalentPoolRequest $request, TalentPool $talentPool): RedirectResponse
    {
        $talentPool->update($request->validated());

        return redirect()->route('recruitment.talent-pools.index')
            ->with('success', 'Talent pool updated successfully.');
    }

    public function destroyTalentPool(TalentPool $talentPool): RedirectResponse
    {
        $talentPool->delete();

        return redirect()->route('recruitment.talent-pools.index')
            ->with('success', 'Talent pool deleted successfully.');
    }
}
