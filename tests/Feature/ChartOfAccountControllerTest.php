<?php

namespace Tests\Unit;

use App\Http\Controllers\ChartOfAccountController;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Mockery;
use Tests\TestCase;
use Exception;

class ChartOfAccountControllerTest extends TestCase
{
    /** @test */
    /** @test */
    public function it_returns_index_view_with_chart_of_accounts()
    {
        Auth::shouldReceive('user')->andReturn((object)['id' => 1]);

        $dummyData = collect([
            (object)[
                'code' => '1001',
                'name' => 'Cash',
                'category' => (object)[
                    'name' => 'Asset',
                    'type' => 'income'
                ]
            ],
            (object)[
                'code' => '2001',
                'name' => 'Accounts Payable',
                'category' => (object)[
                    'name' => 'Liability',
                    'type' => 'expense'
                ]
            ]
        ]);

        $mock = Mockery::mock('alias:' . ChartOfAccount::class);
        $mock->shouldReceive('with')->with('category')->andReturnSelf();
        $mock->shouldReceive('where')->with('user_id', 1)->andReturnSelf();
        $mock->shouldReceive('get')->andReturn($dummyData);

        $controller = new ChartOfAccountController();
        $response = $controller->index();

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('coa.index', $response->name());
        $this->assertArrayHasKey('chartOfAccounts', $response->getData());
        $this->assertCount(2, $response->getData()['chartOfAccounts']);
    }


    /** @test */
    public function it_handles_exception_when_chart_of_account_fails()
    {
        Auth::shouldReceive('user')->andReturn((object)['id' => 1]);

        $coaMock = Mockery::mock('alias:' . ChartOfAccount::class);
        $coaMock->shouldReceive('with')
            ->with('category')
            ->once()
            ->andThrow(new Exception('DB Error'));

        $controller = new ChartOfAccountController();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('DB Error');

        $controller->index();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
