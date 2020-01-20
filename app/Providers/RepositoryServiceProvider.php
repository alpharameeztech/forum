<?php

namespace App\Providers;

use App\Interfaces\AppearanceRepositoryInterface;
use App\Interfaces\PublisherRepositoryInterface;
use App\Interfaces\ReplyRepositoryInterface;
use App\Interfaces\BrandingRepositoryInterface;
use App\Interfaces\ThemeSettingRepositoryInterface;
use App\Repositories\AppearanceRepository;
use App\Repositories\ChannelRepository;
use App\Interfaces\ChannelRepositoryInterface;
use App\Interfaces\ThreadRepositoryInterface;
use App\Interfaces\MemberRepositoryInterface;
use App\Repositories\MemberRepository;
use App\Repositories\PublisherRepository;
use App\Repositories\ReplyRepository;
use App\Repositories\BrandingRepository;
use App\Repositories\ThemeSettingRepository;
use App\Repositories\ThreadRepository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            ChannelRepositoryInterface::class,
            ChannelRepository::class
        );

        $this->app->bind(
            ThreadRepositoryInterface::class,
            ThreadRepository::class
        );

        $this->app->bind(
            ReplyRepositoryInterface::class,
            ReplyRepository::class
        );

        $this->app->bind(
            PublisherRepositoryInterface::class,
            PublisherRepository::class
        );

        $this->app->bind(
            MemberRepositoryInterface::class,
            MemberRepository::class
        );

        $this->app->bind(
            BrandingRepositoryInterface::class,
            BrandingRepository::class
        );

        $this->app->bind(
            ThemeSettingRepositoryInterface::class,
            ThemeSettingRepository::class
        );

        $this->app->bind(
            AppearanceRepositoryInterface::class,
            AppearanceRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
