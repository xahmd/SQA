<article>
	<div class="flex items-start pb-8 pt-4">
		<a class="w-1/3" href="{{ route("recipes.show", ["slug" => $recipe->slug], false) }}">
			<img class="transition-all hover:opacity-80" src="{{ $recipe->image_url }}" alt="{{ htmlentities(\Illuminate\Support\Str::title($recipe->title)) }}" title="{{ htmlentities(\Illuminate\Support\Str::title($recipe->title)) }}">
		</a>
		<div class="w-2/3 pl-6 pr-3">
			<a href="{{ route("recipes.show", ["slug" => $recipe->slug], false) }}" class="block pb-1 text-2xl font-medium text-amber-600 transition-all hover:text-amber-500">{{ \Illuminate\Support\Str::title($recipe->title) }}</a>
			<p class="py-1 text-sm font-medium">{{ date("F d, Y", strtotime($recipe->created_at)) }}</p>
			<div class="py-2 text-sm uppercase text-neutral-500">
				<span class="inline-flex items-center px-1"><i class="fa-solid fa-hourglass-half mr-1"></i>{{ $recipe->cooking_time }}</span>
				<span class="px-1"><i class="fa-solid fa-sliders mr-1"></i>{{ $recipe->difficulty_level }}</span>
				<span class="px-1"><i class="fa-solid fa-users mr-1"></i>{{ $recipe->serving_size }}</span>
				<span class="px-1"><i class="fa-solid fa-tag fa-flip-horizontal mr-1"></i>
					@foreach ($recipe->categories as $key => $category)
						<a href="{{ route("recipes.index_category", ["category" => $category->slug], false) }}" class="inline-flex items-center hover:underline">{{ $category->label }}</a>{{ $key < count($recipe->categories) - 1 ? "," : "" }}
					@endforeach
				</span>
			</div>
			<p class="py-1 text-lg font-extralight">{{ $recipe->description }}</p>
		</div>
	</div>
</article>
