<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'LaraBBS') - {{ \OptimistDigital\NovaSettings\Models\Settings::getValueForKey('admin_name') }}</title>
    <meta name="description" content="@yield('description', \OptimistDigital\NovaSettings\Models\Settings::getValueForKey('seo_description'))" />
    <meta name="keyword" content="@yield('keyword', \OptimistDigital\NovaSettings\Models\Settings::getValueForKey('seo_keyword'))" />
  
    <!-- Styles -->
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