<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
        $this->app->bind('Repositories\Contracts\AlbumRepositoryInterface', 'Repositories\Criteria\AlbumRepository');
        $this->app->bind('Repositories\Contracts\ImageRepositoryInterface', 'Repositories\Criteria\ImageRepository');
        $this->app->bind('Repositories\Contracts\ProfileRepositoryInterface', 'Repositories\Criteria\ProfileRepository');
        $this->app->bind('Repositories\Contracts\ItemRepositoryInterface', 'Repositories\Criteria\ItemRepository');
        $this->app->bind('Repositories\Contracts\UserRepositoryInterface', 'Repositories\Criteria\UserRepository');
        $this->app->bind('Repositories\Contracts\MessageRepositoryInterface', 'Repositories\Criteria\MessageRepository');
        $this->app->bind('Repositories\Contracts\ThreadRepositoryInterface', 'Repositories\Criteria\ThreadRepository');
        $this->app->bind('Repositories\Contracts\NotificationRepositoryInterface', 'Repositories\Criteria\NotificationRepository');
        $this->app->bind('Repositories\Contracts\AdminRepositoryInterface', 'Repositories\Criteria\AdminRepository');
        $this->app->bind('Repositories\Contracts\BillboardRepositoryInterface', 'Repositories\Criteria\BillboardRepository');
        $this->app->bind('Repositories\Contracts\BookmarkRepositoryInterface', 'Repositories\Criteria\BookmarkRepository');
        $this->app->bind('Repositories\Contracts\PostRepositoryInterface', 'Repositories\Criteria\PostRepository');
        $this->app->bind('Repositories\Contracts\SubscriptionRepositoryInterface', 'Repositories\Criteria\SubscriptionRepository');
        $this->app->bind('Repositories\Contracts\VoteRepositoryInterface', 'Repositories\Criteria\VoteRepository');
        $this->app->bind('Repositories\Contracts\CommentRepositoryInterface', 'Repositories\Criteria\CommentRepository');
        $this->app->bind('Repositories\Contracts\BillboardCategoryRepositoryInterface', 'Repositories\Criteria\BillboardCategoryRepository');
        $this->app->bind('Repositories\Contracts\FavorRepositoryInterface', 'Repositories\Criteria\FavorRepository');

        // $this->app->bind('Repositories\Contracts\BidRepositoryInterface', 'Repositories\Criteria\BidRepository');
    }
}
