<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- nova_settings -->
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LaraBBS') - {{ \OptimistDigital\NovaSettings\Models\Settings::getValueForKey('admin_name') }}</title>
    <meta name="description" content="@yield('description', \OptimistDigital\NovaSettings\Models\Settings::getValueForKey('seo_description'))" />
    <meta name="keyword" content="@yield('keyword', \OptimistDigital\NovaSettings\Models\Settings::getValueForKey('seo_keyword'))" />  --}}
    <!-- nova_settings -->
    {{-- origin_settings --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'LaraBBS') - {{ setting('site_name', 'Laravel 进阶教程') }}</title>
    <meta name="description" content="@yield('description', setting('seo_description', 'LaraBBS 爱好者社区。'))" />
    <meta name="keywords" content="@yield('keyword', setting('seo_keyword', 'LaraBBS,社区,论坛,开发者论坛'))" />
    {{-- origin_settings --}}
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @yield('styles')

  </head>

  <body>
    <div id="app" class="{{ route_class() }}-page">

      @include('layouts._header')

      <div class="container">

        @include('shared._messages')

        @yield('content')

      </div>

      @include('layouts._footer')
    </div>
    
    {{-- sudo-su管理面板 --}}
    @if(app()->isLocal())
      @include('sudosu::user-selector')
    @endif

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>

    @yield('scripts')

  </body>
</html>