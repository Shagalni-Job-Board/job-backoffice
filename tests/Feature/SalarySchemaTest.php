<?php

use App\Models\User;
use App\Models\company;
use App\Models\job_category;
use App\Models\job_vacancy;
use Illuminate\Support\Facades\Schema;

test('salary is stored and compared as an integer', function () {
    expect(Schema::getColumnType('job_vacancies', 'salary'))->toBe('integer');

    $owner = User::create([
        'name' => 'Salary Test Owner',
        'email' => 'salary-test-owner@example.test',
        'password' => 'password',
        'role' => 'company-owner',
    ]);
    $company = company::create([
        'name' => 'Salary Test Company',
        'address' => '1 Test Street',
        'industry' => 'Technology',
        'description' => 'Test company',
        'ownerID' => $owner->id,
    ]);
    $category = job_category::create(['name' => 'Salary Test Category']);

    foreach ([9, 100, 20] as $salary) {
        job_vacancy::create([
            'title' => "Salary test {$salary}",
            'description' => 'Test vacancy',
            'location' => 'Remote',
            'salary' => $salary,
            'type' => 'Remote',
            'companyID' => $company->id,
            'categoryID' => $category->id,
        ]);
    }

    expect(job_vacancy::where('salary', '>', 10)->orderBy('salary')->pluck('salary')->all())
        ->toBe([20, 100]);
});
