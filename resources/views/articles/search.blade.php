<!DOCTYPE html>
<html lang="en">

<head>
    <title>Recipes</title>
    <meta property="og:title" content="Cuisine Compass | Taste the World | Food Blog" />
    <meta property="og:description" content="Cuisine Compass | Taste the World | Food Blog" />
    <meta property="og:image" content="" />
    @include('inc.common_head_tags')
</head>

<body>
    @include('inc.header')
    <div class="max-w-6xl mt-10 mx-auto flex flex-wrap">
        <div class="w-2/3 pr-5">
            @isset($search_message)
                <div class="pt-2 pb-4 text-3xl font-medium">
                    <h4>{{ $search_message }}</h4>
                </div>
            @endisset
            <main class="bg-white pb-10">
                @foreach ($articles as $article)
                    @include('articles.card', ['article' => $article])
                @endforeach
                @if ($articles->hasPages())
                    <section class="flex py-3">
                        <ul class="flex mx-auto border rounded-lg overflow-hidden">
                            @php
                                $last_page = $articles->lastPage();
                                $current_page = $articles->currentPage();
                                $start = max(1, $current_page - 2);
                                $end = min($last_page, $start + 4);
                                $start = max(1, $end - 4);
                            @endphp
                            @if ($articles->onFirstPage())
                                <li>
                                    <span class="block px-3 py-2 text-sm border-r text-neutral-400 cursor-default">
                                        <i class="fa-regular fa-angles-left"></i>
                                    </span>
                                </li>
                                <li>
                                    <span class="block px-3 py-2 text-sm border-r text-neutral-400 cursor-default">
                                        <i class="fa-regular fa-angle-left"></i>
                                    </span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $articles->url(1) }}"
                                        class="block px-3 py-2 text-sm border-r hover:bg-amber-300">
                                        <i class="fa-regular fa-angles-left"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $articles->previousPageUrl() }}"
                                        class="block px-3 py-2 text-sm border-r hover:bg-amber-300">
                                        <i class="fa-regular fa-angle-left"></i>
                                    </a>
                                </li>
                            @endif
                            @for ($i = $start; $i <= $end; $i++)
                                @if ($i == $current_page)
                                    <li>
                                        <span class="block px-3 py-2 text-sm border-r bg-amber-300 cursor-default">
                                            {{ $i }}
                                        </span>
                                    </li>
                                @else
                                    <li>
                                        <a class="block px-3 py-2 text-sm border-r hover:bg-amber-300"
                                            href="{{ $articles->url($i) }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endif
                            @endfor
                            @if ($current_page == $last_page)
                                <li>
                                    <span class="block px-3 py-2 text-sm border-r text-neutral-400 cursor-default">
                                        <i class="fa-regular fa-angle-right"></i>
                                    </span>
                                </li>
                                <li>
                                    <span class="block px-3 py-2 text-sm border-r text-neutral-400 cursor-default">
                                        <i class="fa-regular fa-angles-right"></i>
                                    </span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $articles->nextPageUrl() }}"
                                        class="block px-3 py-2 text-sm border-r hover:bg-amber-300">
                                        <i class="fa-regular fa-angle-right"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $articles->url($last_page) }}"
                                        class="block px-3 py-2 text-sm border-r hover:bg-amber-300">
                                        <i class="fa-regular fa-angles-right"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </section>
                @endif
            </main>
        </div>
        <div class="w-1/3 pl-5">
            @include('inc.aside')
        </div>
    </div>
    @include('inc.footer')
</body>

</html>
