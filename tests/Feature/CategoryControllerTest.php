<?php

namespace Tests\Unit;

use App\Http\Controllers\CategoryController;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Mockery;
use Tests\TestCase;
use Exception;

class CategoryControllerTest extends TestCase
{
    /** @test */
    public function it_returns_index_view_with_categories()
    {
        //Simulasi data user
        Auth::shouldReceive('user')->once()->andReturn((object)['id' => 1]);

        // Data dummy
        $dummyCategories = collect([
            (object) ['name' => 'Food', 'type' => 'expense'],
            (object) ['name' => 'Salary', 'type' => 'income'],
        ]);

        // Mock Category model 
        $categoryMock = Mockery::mock('alias:' . Category::class);
        $categoryMock->shouldReceive('where')
            ->with('user_id', 1)
            ->once()
            ->andReturnSelf();
        $categoryMock->shouldReceive('get')
            ->once()
            ->andReturn($dummyCategories);


        $controller = new CategoryController();
        $response = $controller->index();

        // Cek response
        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('category.index', $response->name());
        $this->assertArrayHasKey('categories', $response->getData());
        $this->assertCount(2, $response->getData()['categories']);
    }

    /** @test */
    public function it_handles_exception_when_category_all_fails()
    {
        //simulasi data user
        Auth::shouldReceive('user')->once()->andReturn((object)['id' => 1]);

        // Mock Category model 
        $categoryMock = Mockery::mock('alias:' . Category::class);
        $categoryMock->shouldReceive('where')
            ->with('user_id', 1)
            ->once()
            ->andThrow(new Exception('DB Error'));

        // Uji exception
        $controller = new CategoryController();
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
