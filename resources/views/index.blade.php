<!DOCTYPE html>
<html lang="en">

<head>
	<title>Cuisine Compass | Taste the World | Food Blog</title>
	<meta property="og:title" content="Cuisine Compass | Taste the World | Food Blog" />
	<meta property="og:description" content="Cuisine Compass | Taste the World | Food Blog" />
	<meta property="og:image" content="" />
	@include("inc.common_head_tags")
	<script>
		async function save_request(table, id) {
			const response = await fetch("/api/save", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					"Accept": "application/json",
					"X-CSRF-Token": "{{ csrf_token() }}"
				},
				body: JSON.stringify({
					"table": table,
					"id": id,
				})
			})
			const data = await response.json();
			return data.saved ? (data.saved === 'saved' ? 1 : 0) : -1;

		}
		async function save(event, table, id) {
			const i = await event.currentTarget.querySelector("i")
			const className = i.className
			i.className = "fa-solid fa-spinner-third fa-spin fa-lg"
			const save = await save_request(table, id)
			if (save === 1) i.className = className.replace("fa-regular", "fa-solid")
			else if (save === 0) i.className = className.replace("fa-solid", "fa-regular")
			else i.className = className
		}
	</script>
</head>

<body>
	@include("inc.header")
	@if ($featured_recipe)
		<section class="relative h-[600px] overflow-hidden">
			<img class="h-full w-full scale-[1.05] object-cover blur-[3px]" src="{{ $featured_recipe->image_url }}" alt="{{ htmlentities($featured_recipe->title) }}" title="{{ htmlentities($featured_recipe->title) }}">
			<div class="absolute left-0 top-0 h-full w-full bg-blue-500 bg-opacity-20"></div>
			<div class="justify-left absolute left-1/2 top-1/2 flex w-full max-w-6xl -translate-x-1/2 -translate-y-1/2">
				<div class="mr-auto w-[500px] max-w-full bg-white p-8">
					<div class="text-sm uppercase text-neutral-500">
						<span class="mr-1 inline-flex items-center"><i class="fa-solid fa-hourglass-half mr-1"></i>{{ $featured_recipe->cooking_time }} mins</span>
						<span class="inline-flex items-center"><i class="fa-solid fa-tag fa-flip-horizontal mr-1"></i>
							@foreach ($featured_recipe->categories as $key => $category)
								<span>{{ $category->label }}</span>
								@if ($key < count($featured_recipe->categories) - 1)
									<span>,&nbsp;</span>
								@endif
							@endforeach
						</span>
					</div>
					<h3 class="py-3 text-5xl font-light leading-tight">{{ $featured_recipe->title }}</h3>
					<p class="py-2 text-sm leading-tight text-neutral-600">{{ $featured_recipe->description }}</p>
					<div class="flex items-center py-3 text-sm text-neutral-500">
						<time datetime="2023-10-12 21:00">{{ $featured_recipe->created_at->diffForHumans() }}</time>
						<div class="ml-auto"><a href="/" class="mr-1 p-1"><i class="fa-solid fa-share-nodes fa-lg"></i></a>
							@auth
								<button class="w-5" type="button" onclick="save(event, 'recipes', '{{ $featured_recipe->id }}')">
									@if (\App\Http\Controllers\SaverController::is_saved("recipes", $featured_recipe->id))
										<i class="fa-solid fa-bookmark fa-lg"></i>
									@else
										<i class="fa-regular fa-bookmark fa-lg"></i>
									@endif
								</button>
							@endauth
						</div>
					</div>
					<div class="py-1 text-center"><a href="{{ route("recipes.show", ["slug" => $featured_recipe->slug], false) }}" class="inline-block bg-amber-400 px-7 py-3 transition-all hover:bg-amber-300 hover:text-neutral-600">See the Recipe <i class="fa-solid fa-angles-right fa-xs"></i></a></div>
				</div>
			</div>
		</section>
	@endif
	<section class="mx-auto max-w-6xl py-10">
		<div class="grid gap-4 px-6 md:grid-cols-2 md:gap-8 lg:grid-cols-3 lg:gap-12">
			@foreach ($articles as $article)
				<article class="">
					<img src="{{ $article->image_url }}" alt="" class="mb-3 block h-64 w-full object-cover">
					<div class="text-sm uppercase text-neutral-500">
						@foreach ($article->categories as $category)
							<a href="/category/{{ $category->slug }}" class="inline-flex items-center"><i class="fa-solid fa-tag fa-flip-horizontal mr-1"></i> {{ $category->label }}</a>
						@endforeach
					</div>
					<h3 class="py-2 text-2xl font-light leading-tight"><?= $article->title ?></h3>
					<p class="line-clamp-2 py-1 text-sm leading-tight text-neutral-600">{{ Str::of(strip_tags($article->content))->limit() }}</p>
					<div class="flex items-center py-3 text-sm text-neutral-500">
						<time datetime="2023-10-12 21:00">{{ $article->created_at->diffForHumans() }}</time>
						<div class="ml-auto">
							<a href="/" class="mr-1 p-1"><i class="fa-solid fa-share-nodes fa-lg"></i></a>
							@auth
								<button type="button" onclick="save(event, 'articles', '{{ $article->id }}')" class="inline w-7 p-1">
									@if (\App\Http\Controllers\SaverController::is_saved("articles", $article->id))
										<i class="fa-solid fa-bookmark fa-lg"></i>
									@else
										<i class="fa-regular fa-bookmark fa-lg"></i>
									@endif
								</button>
							@endauth
						</div>
					</div>
					<div class="py-1 text-center">
						<a href="{{ route("articles.show", ["slug" => $article->slug], false) }}" class="inline-block bg-cyan-400 px-7 py-3 transition-all hover:bg-cyan-300 hover:text-neutral-600">Read the Article<i class="fa-solid fa-angles-right fa-xs"></i></a>
					</div>
				</article>
			@endforeach
		</div>
	</section>
	<section class="relative h-[700px]">
		<img src="/images/white-ceramic-bowl-with-food.jpg" alt="two sliced breads with avocado on top" class="h-full w-full object-cover">
		<div class="absolute left-1/2 top-0 mx-auto flex h-full w-full max-w-6xl -translate-x-1/2 items-center justify-start">
			<div class="w-[500px] max-w-full bg-white bg-opacity-60 p-5">
				<h3 class="text-5xl font-semibold leading-tight text-neutral-800">The newest vegan recipes books</h3>
				<p class="py-5 text-lg text-neutral-600">Check out my newest vegan recipes books</p>
				<div class="grid grid-cols-3 gap-5 py-5">
					<div class="flex h-60 items-center justify-center border-4 border-yellow-200 bg-yellow-100 text-2xl font-extralight">Book 1</div>
					<div class="flex h-60 items-center justify-center border-4 border-yellow-200 bg-yellow-100 text-2xl font-extralight">Book 2</div>
					<div class="flex h-60 items-center justify-center border-4 border-yellow-200 bg-yellow-100 text-2xl font-extralight">Book 3</div>
				</div>
			</div>
		</div>
	</section>
	<div class="mx-auto grid max-w-6xl gap-5 py-10 md:grid-cols-3">
		<main class="col-span-2 md:pl-1">
			<div class="relative mb-3 flex items-baseline after:block after:h-1 after:flex-1 after:bg-neutral-800 after:content-['']">
				<span class="mr-3 block text-lg font-semibold">Recent Recipes</span>
			</div>
			@foreach ($recipes as $recipe)
				<article class="my-5 flex flex-wrap">
					<div class="w-full sm:w-2/5">
						<a href="{{ route("recipes.show", ["slug" => $recipe->slug], false) }}" class="block relative h-0 pt-[50%] sm:pt-[70%] hover:opacity-80 transition-all">
							<img src="{{ $recipe->image_url }}" alt="{{ htmlentities($recipe->title) }}" class="absolute left-0 top-0 block h-full w-full object-cover">
						</a>
					</div>
					<div class="flex-0 w-full px-5 sm:w-3/5">
						<div class="py-2 text-xs uppercase text-neutral-500">
							<span class="mr-1 inline-flex items-center"><i class="fa-solid fa-hourglass-half mr-1"></i>{{ $recipe->cooking_time }} mins</span>
							<span><i class="fa-solid fa-tag fa-flip-horizontal mr-1"></i>
								@foreach ($recipe->categories as $key => $category)
									<a href="/recipes/category/{{ $category->slug }}" class="inline-flex items-center">
										{{ $category->label }}
									</a>
									@if ($key < count($recipe->categories) - 1)
										<span>,&nbsp;</span>
									@endif
								@endforeach
							</span>
						</div>
						<a href="{{ route("recipes.show", ["slug" => $recipe->slug], false) }}" class="block py-2 text-xl font-semibold hover:opacity-80 transition-all">{{ $recipe->title }}</a>
						<p class="py-1 text-sm text-neutral-600">{{ $recipe->description }}</p>
						@auth
							<div>
								<button type="button" onclick="save(event, 'recipes', '{{ $recipe->id }}')">
									@if (\App\Http\Controllers\SaverController::is_saved("recipes", $recipe->id))
										<span class="inline-block w-5"><i class="fa-solid fa-bookmark"></i></span> Saved
									@else
										<span class="inline-block w-5"><i class="fa-regular fa-bookmark"></i></span> Save
									@endif
								</button>
							</div>
						@endauth
					</div>
				</article>
			@endforeach
		</main>
		@include("inc.aside")
	</div>
	<section class="bg-slate-200 py-10">
		<div class="mx-auto max-w-6xl text-center">
			<h3 class="py-1 text-2xl font-medium">New Recipe Every Wednesday</h3>
			<p class="py-2 text-sm text-neutral-800">Get ready for a culinary spectacle! Every week, we bring you a tantalizing new recipe that'll set your taste buds on fire. Join us and savor the thrill of culinary creation!</p>
		</div>
		<div class="mx-auto my-6 flex max-w-6xl items-center justify-between">
			<div class="w-1/2">
				<div class="relative h-0 pt-[56.25%]">
					<div class="absolute left-0 top-0 h-full w-full p-2">
						<iframe class="h-full w-full" src="https://player.vimeo.com/video/1002410521" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
					</div>
				</div>
			</div>
			<div class="w-1/2">
				<div class="relative h-0 pt-[56.25%]">
					<div class="absolute left-0 top-0 h-full w-full p-2">
						<iframe class="h-full w-full" src="https://player.vimeo.com/video/1019026214" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="p-10">
		<div class="mx-auto max-w-6xl">
			<h3 class="mb-3 flex items-center py-3 text-center text-xl font-medium">
				<span class="h-1 flex-1 bg-slate-200"></span>
				<span class="px-3">The most popular recipes</span>
				<span class="h-1 flex-1 bg-slate-200"></span>
			</h3>
			<div class="flex flex-wrap justify-center">
				@foreach ($popular_recipes as $recipe)
					<div class="w-1/2 p-2.5 md:w-1/4 lg:w-1/6">
						<div>
							<img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" title="{{ $recipe->title }}" class="block h-32 w-full border object-cover">
							<a href="{{ route("recipes.show", ["slug" => $recipe->slug], false) }}" class="block py-2 text-center text-lg font-medium transition-all hover:text-gray-500">{{ $recipe->title }}</a>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</section>
	@include("inc.footer")
</body>

</html>
