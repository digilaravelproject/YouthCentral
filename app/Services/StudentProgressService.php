<?php

namespace App\Services;

use App\Models\Student;
use App\Models\ProgressActivity;
use App\Models\StudentActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentProgressService
{
    /**
     * Compute the progress state for a given student.
     *
     * @param Student $student
     * @return array
     */
    public function getProgress(Student $student)
    {
        $rules = ProgressActivity::all();
        $breakdown = [];
        $totalConfigured = 0;
        $totalEarned = 0;

        foreach ($rules as $rule) {
            $type = $rule->activity_type;
            $percentage = $rule->percentage;
            $limit = $rule->max_limit;
            $title = $rule->title;

            // Compute current count for this student
            if ($type === 'login_streak') {
                $currentCount = $student->login_streak;
            } else {
                $currentCount = StudentActivity::where('student_id', $student->id)
                    ->where('activity_type', $type)
                    ->distinct('reference_id')
                    ->count('reference_id');
            }

            // Earned percentage
            $ratio = $limit > 0 ? min($currentCount / $limit, 1.0) : 0;
            $earned = round($ratio * $percentage, 1);

            $totalConfigured += $percentage;
            $totalEarned += $earned;

            // Status label
            if ($currentCount === 0) {
                $status = 'Not Started';
                $badgeClass = 'bg-secondary';
            } elseif ($currentCount >= $limit) {
                $status = 'Completed';
                $badgeClass = 'bg-success';
            } else {
                $status = 'In Progress';
                $badgeClass = 'bg-info';
            }

            $breakdown[] = [
                'rule_id' => $rule->id,
                'activity_type' => $type,
                'title' => $title,
                'limit' => $limit,
                'current_count' => $currentCount,
                'percentage' => $percentage,
                'earned_percentage' => $earned,
                'status' => $status,
                'badge_class' => $badgeClass,
            ];
        }

        // overall dynamic percentage normalized to 0-100%
        $overallPercentage = $totalConfigured > 0 ? round(($totalEarned / $totalConfigured) * 100) : 0;
        $overallPercentage = min($overallPercentage, 100);

        return [
            'activities' => $breakdown,
            'total_configured_percentage' => $totalConfigured,
            'total_earned_percentage' => $totalEarned,
            'overall_percentage' => $overallPercentage,
        ];
    }

    /**
     * Check if a student can perform an activity based on limits and log if allowed.
     *
     * @param Student $student
     * @param string $activityType
     * @param int|null $referenceId
     * @return array
     */
    public function checkLimitAndLog(Student $student, string $activityType, $referenceId = null)
    {
        $rule = ProgressActivity::where('activity_type', $activityType)->first();

        // If no tracking rule is set for this activity type, it is allowed without limit/logging
        if (!$rule) {
            return ['allowed' => true, 'logged' => false];
        }

        // If a reference ID is provided, check if they already performed this action on this resource
        if ($referenceId) {
            $alreadyLogged = StudentActivity::where('student_id', $student->id)
                ->where('activity_type', $activityType)
                ->where('reference_id', $referenceId)
                ->exists();

            if ($alreadyLogged) {
                // Already did this action on this resource; allowed to repeat (e.g. re-download/re-view)
                return ['allowed' => true, 'logged' => false];
            }
        }

        // Calculate unique actions of this type
        $currentCount = StudentActivity::where('student_id', $student->id)
            ->where('activity_type', $activityType)
            ->distinct('reference_id')
            ->count('reference_id');

        if ($currentCount >= $rule->max_limit) {
            return [
                'allowed' => false,
                'message' => "Your limit of {$rule->max_limit} for \"{$rule->title}\" has been reached. Please contact the administrator."
            ];
        }

        // Log the action
        StudentActivity::create([
            'student_id' => $student->id,
            'activity_type' => $activityType,
            'reference_id' => $referenceId,
        ]);

        return ['allowed' => true, 'logged' => true];
    }

    /**
     * Increment the daily login streak of a student.
     *
     * @param Student $student
     * @return void
     */
    public function incrementLoginStreak(Student $student)
    {
        $today = Carbon::today();
        $lastLoginDate = $student->last_login_date ? Carbon::parse($student->last_login_date) : null;

        if (!$lastLoginDate) {
            // First login ever
            $student->update([
                'login_streak' => 1,
                'last_login_date' => $today->toDateString(),
            ]);
        } else {
            $diffInDays = $today->diffInDays($lastLoginDate);

            if ($diffInDays === 1) {
                // Logged in yesterday; increment streak
                $student->update([
                    'login_streak' => $student->login_streak + 1,
                    'last_login_date' => $today->toDateString(),
                ]);
            } elseif ($diffInDays > 1) {
                // Lost streak; reset to 1
                $student->update([
                    'login_streak' => 1,
                    'last_login_date' => $today->toDateString(),
                ]);
            }
            // If diffInDays is 0 (logged in today already), we leave it as is.
        }
    }
}
