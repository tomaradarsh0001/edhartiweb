{{-- <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">
        {{ ucfirst(View::yieldContent('title') ?: 'Dashboard') }}
    </div> --}}
<style>
    .breadcrumb-item.active span {
        font-weight: 500;
    }
</style>
<div class="ps-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    <i class="bx bx-home-alt"></i>
                </a>
            </li>

            @php
                $breadcrumbs = generateBreadcrumbs();
                $breadcrumbCount = count($breadcrumbs);
            @endphp

            @foreach ($breadcrumbs as $index => $breadcrumb)
                <li class="breadcrumb-item {{ $index === $breadcrumbCount - 1 ? 'active' : '' }}"
                    aria-current="{{ $index === $breadcrumbCount - 1 ? 'page' : '' }}">
                    @if ($breadcrumb['url'])
                        <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a>
                    @else
                        <span>{{ $breadcrumb['name'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
</div>
