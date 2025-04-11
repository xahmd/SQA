<header class="mx-auto max-w-6xl">
	<div class="flex w-full flex-wrap items-center justify-between border-b py-10">
		<div class="mb-5 w-full lg:mb-0 lg:mr-1 lg:w-auto">
			<a href="/" class="block"><img src="/images/logo.svg" alt="Logo" class="mx-auto block block h-16 lg:pl-1"></a>
		</div>
		<div id="search_form" class="mb-4 w-full md:my-0 md:ml-1 md:mr-auto md:w-auto lg:ml-auto lg:mr-0">
			<form class="flex border" action="{{ route("search", [], false) }}">
				<select name="category" id="category" class="cursor-pointer border-2 border-white bg-neutral-100 px-3 py-2 text-xs font-semibold uppercase outline-none transition-all hover:bg-neutral-200 lg:px-2 lg:py-3">
					@php
						$categories = \App\Models\Category::withCount("recipes")->orderBy("recipes_count", "desc")->take(6)->get();
						$cat = request("category", "");
						$q = request("q", "");
					@endphp
					<option value="" @selected($cat == "")>All Categories</option>
					@foreach ($categories as $category)
						<option class="text-[2px]" disabled></option>
						<option value="{{ $category->slug }}" @selected($cat == $category->slug)>{{ $category->label }}</option>
					@endforeach
				</select>
				<input placeholder="Search recipes..." type="text" id="q" name="q" value="{{ $q }}" class="w-72 flex-1 border-none bg-none px-1 py-3 text-sm focus:outline-none">
				<button type="submit" class="h-12 w-12 bg-yellow-300 transition-all hover:bg-amber-200">
					<i class="fa-solid fa-magnifying-glass"></i>
				</button>
			</form>
		</div>
		<div class="w-full md:w-auto">
			<ul class="flex items-center justify-center pl-3">
				@auth
					<li class="border-r">
						<a href="{{ route("auth.profile", [], false) }}" class="px-3 py-2 transition-all hover:text-neutral-500">{{ auth()->user()->firstname }}</a>
					</li>
					<li>
						<form action="{{ route("auth.logout", [], false) }}" method="POST">
							@csrf
							<button type="submit" class="px-3 py-2 transition-all hover:text-neutral-500">
								Logout
							</button>
						</form>
					</li>
				@endauth
				@guest
					<li class="border-r">
						<a href="{{ route("auth.login", [], false) }}" class="px-3 py-2 transition-all hover:text-neutral-500">Login</a>
					</li>
					<li>
						<a href="{{ route("auth.register", [], false) }}" class="px-3 py-2 transition-all hover:text-neutral-500">Register</a>
					</li>
				@endguest
			</ul>
		</div>
	</div>
	<nav class="flex items-center flex-wrap md:flex-nowrap">
		<ul class="flex items-center flex-wrap justify-center w-full md:w-auto">
			<li class="mr-3">
				<a href="/" class="block px-3 py-5 text-sm font-bold uppercase text-neutral-600 transition-all hover:bg-amber-300 hover:text-white">
					Home
				</a>
			</li>
			<li class="mr-3">
				<a href="{{ route("categories", [], false) }}" class="block px-3 py-5 text-sm font-bold uppercase text-neutral-600 transition-all hover:bg-amber-300 hover:text-white">
					Categories
				</a>
			</li>
			<li class="mr-3">
				<a href="{{ route("recipes.index", [], false) }}" class="block px-3 py-5 text-sm font-bold uppercase text-neutral-600 transition-all hover:bg-amber-300 hover:text-white">
					Recipes
				</a>
			</li>
			<li class="mr-3">
				<a href="{{ route("articles.index", [], false) }}" class="block px-3 py-5 text-sm font-bold uppercase text-neutral-600 transition-all hover:bg-amber-300 hover:text-white">
					Articles
				</a>
			</li>
			<li class="mr-3">
				<a href="/" class="block px-3 py-5 text-sm font-bold uppercase text-neutral-600 transition-all hover:bg-amber-300 hover:text-white">
					About Us
				</a>
			</li>
			<li>
				<a href="/" class="block px-3 py-5 text-sm font-bold uppercase text-neutral-600 transition-all hover:bg-amber-300 hover:text-white">
					Contact
				</a>
			</li>
		</ul>
		<ul class="ml-auto flex items-center w-full md:w-auto justify-center">
			<li><a href="/" class="mr-1 block p-1 transition-all hover:text-amber-300"><i class="fa-sm fa-brands fa-instagram"></i></a></li>
			<li><a href="/" class="mr-1 block p-1 transition-all hover:text-amber-300"><i class="fa-sm fa-brands fa-facebook-f"></i></a></li>
			<li><a href="/" class="block p-1 transition-all hover:text-amber-300"><i class="fa-sm fa-brands fa-x-twitter"></i></a></li>
			<li><a href="/" class="block p-1 transition-all hover:text-amber-300"><i class="fa-sm fa-brands fa-pinterest"></i></a></li>
			<li><a href="/" class="block p-1 transition-all hover:text-amber-300"><i class="fa-sm fa-brands fa-tiktok"></i></a></li>
			<li><a href="/" class="block p-1 transition-all hover:text-amber-300"><i class="fa-sm fa-brands fa-youtube"></i></a></li>
		</ul>
	</nav>
</header>
