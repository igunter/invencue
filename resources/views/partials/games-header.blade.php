<div class="mb-4 text-center">
    <span class="tier-pill tier-pill-basic"><a href="{{ route('category.show', $category) }}">{{ $category->name }}</a></span>
    <h1 class="h3 mb-1"><i class="{{ $game->icon }}"></i> {{ $game->name }}</h1>
    <p class="text-secondary mb-0">{{ $blurb }}</p>
</div>