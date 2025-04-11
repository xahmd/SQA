<article>
	<div class="flex items-start pb-8 pt-4">
		<a class="w-1/3" href="{{ route("articles.show", ["slug" => $article->slug], false) }}">
			<img class="transition-all hover:opacity-80" src="{{ $article->image_url }}" alt="{{ htmlentities(\Illuminate\Support\Str::title($article->title)) }}" title="{{ htmlentities(\Illuminate\Support\Str::title($article->title)) }}">
		</a>
		<div class="w-2/3 pl-6 pr-3">
			<a href="{{ route("articles.show", ["slug" => $article->slug], false) }}" class="block pb-2 text-2xl font-medium text-amber-600 transition-all hover:text-amber-500">{{ \Illuminate\Support\Str::title($article->title) }}</a>
			<p class="py-2 text-sm font-medium">{{ date("F d, Y", strtotime($article->created_at)) }}</p>
			<div class="py-2 text-sm uppercase text-neutral-500">
				<span class="px-1"><i class="fa-solid fa-tag fa-flip-horizontal mr-1"></i>
					@foreach ($article->categories as $key => $category)
						<a href="{{ route("articles.index_category", ["category" => $category->slug], false) }}" class="inline-flex items-center hover:underline">{{ $category->label }}</a>{{ $key < count($article->categories) - 1 ? "," : "" }}
					@endforeach
				</span>
			</div>
			<p class="py-1 text-lg font-light">hello everyone this is one of the most amazing things to do in life just dont over do it while you are still have the potentionl to do so...</p>
		</div>
	</div>
</article>
