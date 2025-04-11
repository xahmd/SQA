<!DOCTYPE html>
<html lang="en">

<head>
    <title>Show Article</title>
    <meta property="og:title" content="Categories" />
    <meta property="og:description" content="" />
    <meta property="og:image" content="" />
    @include('inc.common_head_tags')
</head>

<body>
    @include('inc.header')
    <div class="max-w-6xl mt-10 mx-auto flex flex-wrap">
        <main class="bg-white pb-10 w-full grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach ($categories as $category)
                <a href="{{ route($category->for . '.index_category', ['category' => $category->slug], false) }}" class="block group relative overflow-hidden rounded-lg">
                    <img class="absolute inset-0 w-full h-full object-cover" src="{{ $category->image_url }}"
                        alt="{{ $category->label }}">
                    <div
                        class="flex justify-center items-center w-full h-48 text-2xl bg-blue-600 bg-opacity-30 text-white relative transition-all group-hover:bg-opacity-50 group-hover:text-4xl leading-5 group-hover:leading-5 whitespace-nowrap">
                        {{ $category->label }}</div>
                    <div class="absolute bottom-0 right-0 flex justify-center items-center">
                        <span
                            class="absolute bottom-0 right-0 bg-gradient-to-tl {{ $category->for === 'recipes' ? 'from-amber-400' : 'from-green-400' }} to-transparent from-50% to-50% block w-[200%] h-[200%]"></span>
                        <span
                            class="text-center block relative p-1 text-white">{{ ucwords(strtolower($category->for)) }}</span>
                    </div>
                </a>
            @endforeach
        </main>
    </div>
    @include('inc.footer')
</body>

</html>
