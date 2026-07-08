<?php

use App\Http\Controllers\AIController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GeoFenceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\ShiftController;
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

    // Attendance Module
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('check-in', [AttendanceController::class, 'checkInView'])->name('check-in');
        Route::post('check-in', [AttendanceController::class, 'storeCheckIn'])->name('check-in.store');
        Route::post('check-out/{employee}', [AttendanceController::class, 'storeCheckOut'])->name('check-out.store');
        Route::get('missing-punches', [AttendanceController::class, 'missingPunchSuggestions'])->name('missing-punches');
        Route::get('employee/{employee}/log', [AttendanceController::class, 'employeeLog'])->name('employee-log');
        Route::get('overtime/list', [AttendanceController::class, 'overtime'])->name('overtime');
        Route::post('overtime/{overtime}/approve', [AttendanceController::class, 'approveOvertime'])->name('overtime.approve');
        Route::get('anomalies/list', [AttendanceController::class, 'anomalies'])->name('anomalies');
        Route::post('anomalies/{anomaly}/resolve', [AttendanceController::class, 'resolveAnomaly'])->name('anomalies.resolve');
        Route::get('predict/{employee}', [AttendanceController::class, 'predictAttendance'])->name('predict');
        Route::get('{record}', [AttendanceController::class, 'show'])->name('show');
    });

    // Shift Management
    Route::prefix('shifts')->name('shifts.')->group(function () {
        Route::get('/', [ShiftController::class, 'index'])->name('index');
        Route::get('create', [ShiftController::class, 'create'])->name('create');
        Route::post('/', [ShiftController::class, 'store'])->name('store');
        Route::get('{shift}/assign', [ShiftController::class, 'assignForm'])->name('assign');
        Route::post('{shift}/assign', [ShiftController::class, 'assignStore'])->name('assign.store');
        Route::delete('{shift}/assign/{assignment}', [ShiftController::class, 'assignRemove'])->name('assign.remove');
        Route::get('{shift}/edit', [ShiftController::class, 'edit'])->name('edit');
        Route::put('{shift}', [ShiftController::class, 'update'])->name('update');
        Route::delete('{shift}', [ShiftController::class, 'destroy'])->name('destroy');
        Route::get('{shift}', [ShiftController::class, 'show'])->name('show');
    });

    // Geo-Fence Management
    Route::prefix('geo-fences')->name('geo-fences.')->group(function () {
        Route::get('/', [GeoFenceController::class, 'index'])->name('index');
        Route::get('create', [GeoFenceController::class, 'create'])->name('create');
        Route::post('/', [GeoFenceController::class, 'store'])->name('store');
        Route::get('{geoFence}/edit', [GeoFenceController::class, 'edit'])->name('edit');
        Route::put('{geoFence}', [GeoFenceController::class, 'update'])->name('update');
        Route::delete('{geoFence}', [GeoFenceController::class, 'destroy'])->name('destroy');
        Route::get('{geoFence}', [GeoFenceController::class, 'show'])->name('show');
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
