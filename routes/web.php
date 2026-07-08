<?php

use App\Http\Controllers\AIController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecruitmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Redirect Breeze dashboard to our recruitment dashboard
Route::get('/dashboard', function () {
    return redirect()->route('recruitment.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Employee Management
    Route::resource('employees', EmployeeController::class);
    Route::get('org-chart', [EmployeeController::class, 'orgChart'])->name('employees.org-chart');

    Route::post('employees/{employee}/contacts', [EmployeeController::class, 'storeContact'])->name('employees.contacts.store');
    Route::delete('employees/{employee}/contacts/{contact}', [EmployeeController::class, 'destroyContact'])->name('employees.contacts.destroy');

    Route::post('employees/{employee}/histories', [EmployeeController::class, 'storeHistory'])->name('employees.histories.store');
    Route::delete('employees/{employee}/histories/{history}', [EmployeeController::class, 'destroyHistory'])->name('employees.histories.destroy');

    Route::post('employees/{employee}/skills', [EmployeeController::class, 'storeSkill'])->name('employees.skills.store');
    Route::delete('employees/{employee}/skills/{skill}', [EmployeeController::class, 'destroySkill'])->name('employees.skills.destroy');

    Route::post('employees/{employee}/documents', [EmployeeController::class, 'storeDocument'])->name('employees.documents.store');
    Route::delete('employees/{employee}/documents/{document}', [EmployeeController::class, 'destroyDocument'])->name('employees.documents.destroy');

    Route::post('employees/{employee}/assets', [EmployeeController::class, 'storeAsset'])->name('employees.assets.store');
    Route::post('employees/{employee}/assets/{asset}/return', [EmployeeController::class, 'returnAsset'])->name('employees.assets.return');

    Route::post('employees/{employee}/exit', [EmployeeController::class, 'initExit'])->name('employees.exit.init');
    Route::post('employees/{employee}/exit/{exit}', [EmployeeController::class, 'updateExit'])->name('employees.exit.update');

    // Recruitment / ATS
    Route::prefix('recruitment')->name('recruitment.')->group(function () {
        Route::get('dashboard', [RecruitmentController::class, 'dashboard'])->name('dashboard');

        Route::get('candidates', [RecruitmentController::class, 'index'])->name('candidates.index');
        Route::get('candidates/create', [RecruitmentController::class, 'create'])->name('candidates.create');
        Route::post('candidates', [RecruitmentController::class, 'store'])->name('candidates.store');
        Route::get('candidates/{candidate}', [RecruitmentController::class, 'show'])->name('candidates.show');
        Route::get('candidates/{candidate}/edit', [RecruitmentController::class, 'edit'])->name('candidates.edit');
        Route::put('candidates/{candidate}', [RecruitmentController::class, 'update'])->name('candidates.update');
        Route::delete('candidates/{candidate}', [RecruitmentController::class, 'destroy'])->name('candidates.destroy');
        Route::post('candidates/{candidate}/advance-stage', [RecruitmentController::class, 'advanceStage'])->name('candidates.advance-stage');
        Route::get('candidates/{candidate}/resume', [RecruitmentController::class, 'resumeDownload'])->name('candidates.resume');

        Route::get('interviews', [RecruitmentController::class, 'interviews'])->name('interviews.index');
        Route::post('candidates/{candidate}/interviews', [RecruitmentController::class, 'storeInterview'])->name('interviews.store');
        Route::put('interviews/{interview}', [RecruitmentController::class, 'updateInterview'])->name('interviews.update');

        Route::post('candidates/{candidate}/offers', [RecruitmentController::class, 'storeOffer'])->name('offers.store');
        Route::get('offers/{offerLetter}/download', [RecruitmentController::class, 'offerDownload'])->name('offers.download');

        Route::post('candidates/{candidate}/verifications', [RecruitmentController::class, 'storeVerification'])->name('verifications.store');
        Route::put('verifications/{verification}', [RecruitmentController::class, 'updateVerification'])->name('verifications.update');

        Route::post('candidates/{candidate}/communications', [RecruitmentController::class, 'storeCommunication'])->name('communications.store');

        Route::get('jobs', [RecruitmentController::class, 'jobs'])->name('jobs.index');
        Route::get('jobs/create', [RecruitmentController::class, 'createJob'])->name('jobs.create');
        Route::post('jobs', [RecruitmentController::class, 'storeJob'])->name('jobs.store');
        Route::get('jobs/{job}', [RecruitmentController::class, 'showJob'])->name('jobs.show');
        Route::get('jobs/{job}/edit', [RecruitmentController::class, 'editJob'])->name('jobs.edit');
        Route::put('jobs/{job}', [RecruitmentController::class, 'updateJob'])->name('jobs.update');
        Route::delete('jobs/{job}', [RecruitmentController::class, 'destroyJob'])->name('jobs.destroy');

        Route::get('talent-pools', [RecruitmentController::class, 'talentPools'])->name('talent-pools.index');
        Route::post('talent-pools', [RecruitmentController::class, 'storeTalentPool'])->name('talent-pools.store');
        Route::put('talent-pools/{talentPool}', [RecruitmentController::class, 'updateTalentPool'])->name('talent-pools.update');
        Route::delete('talent-pools/{talentPool}', [RecruitmentController::class, 'destroyTalentPool'])->name('talent-pools.destroy');
    });

    // AI Features
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('candidates/{candidate}/screening', [AIController::class, 'screening'])->name('screening');
        Route::get('candidates/{candidate}/matching/{job}', [AIController::class, 'matchingScore'])->name('matching');
        Route::get('candidates/{candidate}/questions/{job}', [AIController::class, 'interviewQuestions'])->name('questions');
        Route::get('interviews/{interview}/summary', [AIController::class, 'interviewSummary'])->name('interview-summary');
        Route::get('jobs/{job}/ranking', [AIController::class, 'ranking'])->name('ranking');
        Route::get('candidates/{candidate}/skill-gap/{job}', [AIController::class, 'skillGap'])->name('skill-gap');
        Route::get('candidates/{candidate}/insights', [AIController::class, 'allInsights'])->name('candidate-insights');

        Route::post('candidates/{candidate}/screening/regenerate', [AIController::class, 'regenerateScreening'])->name('screening.regenerate');
        Route::post('candidates/{candidate}/matching/{job}/regenerate', [AIController::class, 'regenerateMatching'])->name('matching.regenerate');
        Route::post('jobs/{job}/ranking/regenerate', [AIController::class, 'regenerateRanking'])->name('ranking.regenerate');
        Route::post('candidates/{candidate}/skill-gap/{job}/regenerate', [AIController::class, 'regenerateSkillGap'])->name('skill-gap.regenerate');
        Route::post('candidates/{candidate}/questions/{job}/regenerate', [AIController::class, 'regenerateQuestions'])->name('questions.regenerate');
    });
});

require __DIR__.'/auth.php';
