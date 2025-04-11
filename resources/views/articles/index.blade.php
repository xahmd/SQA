<!DOCTYPE html>
<html lang="en">

<head>
	<title>Articles</title>
	<meta property="og:title" content="Cuisine Compass | Taste the World | Food Blog" />
	<meta property="og:description" content="Cuisine Compass | Taste the World | Food Blog" />
	<meta property="og:image" content="" />
	@include("inc.common_head_tags")
	<script>
		document.addEventListener("DOMContentLoaded", () => {
			const slider = tns({
				container: '.tiny-slider',
				controlsContainer: ".tiny-slider-control",
				navContainer: ".tiny-slider-nav",
				items: 1,
				slideBy: 'page',
				autoplay: true,
				autoplayButtonOutput: false,
				autoplayHoverPause: true,
			});
		});
	</script>
</head>

<body>
	@include("inc.header")
	<section class="mx-auto mt-10 max-w-6xl">
		<div class="relative bg-gradient-to-l from-[rgba(0,0,0,.4)] via-transparent to-[rgba(0,0,0,.4)]">
			<div class="tiny-slider">
				<div class="relative">
					<img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1493193218435-eb21654c7df6?q=80&w=1740&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="">
					<a href="#" class="relative block bg-gradient-to-t from-[rgba(0,0,0,.7)] to-[rgba(0,0,0,.3)] py-32 transition-all duration-500 hover:pb-36 hover:pt-28">
						<h3 class="py-2 text-center text-3xl text-white">The Best Way to Open an English Muffin Will Blow Your Mind</h3>
						<p class="py-2 text-center font-light text-neutral-300">Published on October 11, 2023&nbsp;&bull;&nbsp;Updated on October 22, 2023</p>
					</a>
				</div>
				<div class="relative">
					<img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1493193218435-eb21654c7df6?q=80&w=1740&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="">
					<a href="#" class="relative block bg-gradient-to-t from-[rgba(0,0,0,.7)] to-[rgba(0,0,0,.3)] py-32 transition-all duration-500 hover:pb-36 hover:pt-28">
						<h3 class="py-2 text-center text-3xl text-white">The Best Way to Open an English Muffin Will Blow Your Mind</h3>
						<p class="py-2 text-center font-light text-neutral-300">Published on October 11, 2023&nbsp;&bull;&nbsp;Updated on October 22, 2023</p>
					</a>
				</div>
				<div class="relative">
					<img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1493193218435-eb21654c7df6?q=80&w=1740&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="">
					<a href="#" class="relative block bg-gradient-to-t from-[rgba(0,0,0,.7)] to-[rgba(0,0,0,.3)] py-32 transition-all duration-500 hover:pb-36 hover:pt-28">
						<h3 class="py-2 text-center text-3xl text-white">The Best Way to Open an English Muffin Will Blow Your Mind</h3>
						<p class="py-2 text-center font-light text-neutral-300">Published on October 11, 2023&nbsp;&bull;&nbsp;Updated on October 22, 2023</p>
					</a>
				</div>
			</div>
			<div class="tiny-slider-nav absolute bottom-0 left-0 right-0 mx-auto w-fit py-1 text-center">
				<button class="group mx-1">
					<span class="block h-3 w-3 rounded-full bg-neutral-400 group-[.tns-nav-active]:bg-white"></span>
				</button>
				<button class="group mx-1">
					<span class="block h-3 w-3 rounded-full bg-neutral-400 group-[.tns-nav-active]:bg-white"></span>
				</button>
				<button class="group mx-1">
					<span class="block h-3 w-3 rounded-full bg-neutral-400 group-[.tns-nav-active]:bg-white"></span>
				</button>
			</div>
			<div class="tiny-slider-control">
				<button class="absolute left-0 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-white"><i class="fa-light fa-angle-left text-7xl"></i></button>
				<button class="absolute right-0 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-white"><i class="fa-light fa-angle-right text-7xl"></i></button>
			</div>
		</div>
	</section>
	<div class="mx-auto mt-10 flex max-w-6xl flex-wrap">
		<div class="w-full md:w-2/3 md:pr-5">
			<main class="bg-white pb-10">
				@foreach ($articles as $article)
					@include("articles.card", ["article" => $article])
				@endforeach
				@if ($articles->hasPages())
					<section class="flex py-3">
						<ul class="mx-auto flex overflow-hidden rounded-lg border">
							@php
								$last_page = $articles->lastPage();
								$current_page = $articles->currentPage();
								$start = max(1, $current_page - 2);
								$end = min($last_page, $start + 4);
								$start = max(1, $end - 4);
							@endphp
							@if ($articles->onFirstPage())
								<li>
									<span class="block cursor-default border-r px-3 py-2 text-sm text-neutral-400">
										<i class="fa-regular fa-angles-left"></i>
									</span>
								</li>
								<li>
									<span class="block cursor-default border-r px-3 py-2 text-sm text-neutral-400">
										<i class="fa-regular fa-angle-left"></i>
									</span>
								</li>
							@else
								<li>
									<a href="{{ $articles->url(1) }}" class="block border-r px-3 py-2 text-sm hover:bg-amber-300">
										<i class="fa-regular fa-angles-left"></i>
									</a>
								</li>
								<li>
									<a href="{{ $articles->previousPageUrl() }}" class="block border-r px-3 py-2 text-sm hover:bg-amber-300">
										<i class="fa-regular fa-angle-left"></i>
									</a>
								</li>
							@endif
							@for ($i = $start; $i <= $end; $i++)
								@if ($i == $current_page)
									<li>
										<span class="block cursor-default border-r bg-amber-300 px-3 py-2 text-sm">
											{{ $i }}
										</span>
									</li>
								@else
									<li>
										<a class="block border-r px-3 py-2 text-sm hover:bg-amber-300" href="{{ $articles->url($i) }}">
											{{ $i }}
										</a>
									</li>
								@endif
							@endfor
							@if ($current_page == $last_page)
								<li>
									<span class="block cursor-default border-r px-3 py-2 text-sm text-neutral-400">
										<i class="fa-regular fa-angle-right"></i>
									</span>
								</li>
								<li>
									<span class="block cursor-default border-r px-3 py-2 text-sm text-neutral-400">
										<i class="fa-regular fa-angles-right"></i>
									</span>
								</li>
							@else
								<li>
									<a href="{{ $articles->nextPageUrl() }}" class="block border-r px-3 py-2 text-sm hover:bg-amber-300">
										<i class="fa-regular fa-angle-right"></i>
									</a>
								</li>
								<li>
									<a href="{{ $articles->url($last_page) }}" class="block border-r px-3 py-2 text-sm hover:bg-amber-300">
										<i class="fa-regular fa-angles-right"></i>
									</a>
								</li>
							@endif
						</ul>
					</section>
				@endif
			</main>
		</div>
		<div class="w-full md:w-1/3 md:pl-5">
			@include("inc.aside")
		</div>
	</div>
	@include("inc.footer")
</body>

</html>
