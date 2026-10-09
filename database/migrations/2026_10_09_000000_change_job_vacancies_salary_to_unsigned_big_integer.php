<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Convert whole-dollar salary strings without coercing ambiguous legacy values.
     */
    public function up(): void
    {
        $invalidSalaries = [];

        foreach (DB::table('job_vacancies')->select('id', 'salary')->cursor() as $vacancy) {
            $salary = (string) $vacancy->salary;

            if (! preg_match('/^(0|[1-9][0-9]*)$/', $salary)
                || strlen($salary) > 20
                || (strlen($salary) === 20 && strcmp($salary, '18446744073709551615') > 0)) {
                $invalidSalaries[] = sprintf('%s (%s)', $vacancy->id, var_export($vacancy->salary, true));
            }
        }

        if ($invalidSalaries !== []) {
            throw new \RuntimeException(
                'Cannot convert job_vacancies.salary to an unsigned integer. '
                .'Fix these non-negative whole-number values and rerun the migration: '
                .implode(', ', $invalidSalaries)
            );
        }

        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->unsignedBigInteger('salary_numeric')->nullable()->after('salary');
        });

        foreach (DB::table('job_vacancies')->select('id', 'salary')->cursor() as $vacancy) {
            DB::table('job_vacancies')
                ->where('id', $vacancy->id)
                ->update(['salary_numeric' => $vacancy->salary]);
        }

        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->dropColumn('salary');
        });

        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->renameColumn('salary_numeric', 'salary');
        });
    }

    public function down(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->string('salary_text')->nullable()->after('salary');
        });

        foreach (DB::table('job_vacancies')->select('id', 'salary')->cursor() as $vacancy) {
            DB::table('job_vacancies')
                ->where('id', $vacancy->id)
                ->update(['salary_text' => (string) $vacancy->salary]);
        }

        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->dropColumn('salary');
        });

        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->renameColumn('salary_text', 'salary');
        });
    }
};
