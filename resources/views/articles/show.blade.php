<!DOCTYPE html>
<html lang="en">

<head>
	<title>Show Article</title>
	<meta property="og:title" content="{{ $article->title }}" />
	<meta property="og:description" content="{{ $article->description }}" />
	<meta property="og:image" content="{{ $article->image_url }}" />
	@include("inc.common_head_tags")
</head>

<body>
	@include("inc.header")
	<div class="mx-auto mt-10 flex max-w-6xl flex-wrap">
		<div class="w-full md:w-2/3 md:pr-5">
			<main class="bg-white pb-10">
				<div class="text-center">
					<span>
						@foreach ($article->categories as $key => $category)
							<a class="text-sm font-bold uppercase text-blue-400" href="{{ route("articles.index_category", ["category" => $category->slug], false) }}">{{ $category->label }}</a>
							@if ($key < count($article->categories) - 1)
								<span>&nbsp;|&nbsp;</span>
							@endif
						@endforeach
					</span>
				</div>
				<h1 class="py-2 text-center text-4xl font-extralight">{{ \Illuminate\Support\Str::title($article->title) }}</h1>
				<p class="py-2 text-center font-light text-neutral-500">Published on {{ date("F d, Y", strtotime($article->created_at)) }}&nbsp;&bull;&nbsp;Updated on {{ date("F d, Y", strtotime($article->created_at)) }}</p>
				<p class="text-center font-light"><span class="whitespace-nowrap">Views:&nbsp;&nbsp;{{ $views }}&nbsp;</i><i class="fa-regular fa-eye"></i></span></p>
				<img src="{{ $article->image_url }}" class="mx-auto my-5 block w-[700px] max-w-full rounded-sm border p-1 shadow" alt="{{ htmlentities($article->title) }}" title="{{ htmlentities($article->title) }}">
				<div class="ck-content py-5 text-lg font-light">
					{!! $article->content !!}
				</div>
				<div class="py-3">
					<span class="font-semibold">Tags:</span>
					@foreach ($article->tags as $tag)
						<a class="rounded bg-neutral-200 px-1 hover:bg-neutral-300" href="{{ route("articles.index_tag", ["tag" => $tag], false) }}">{{ \Illuminate\Support\Str::title($tag) }}</a>
					@endforeach
				</div>
				<section class="py-6">
					<h4 class="py-1 text-2xl"><i class="fa-solid fa-share-nodes mr-2.5"></i>Tell your friends about it!</h4>
					<div class="flex flex-wrap items-center py-2">
						<div class="m-1"><div class="fb-share-button" data-href="https://www.allrecipes.com/article/cup-to-gram-conversions/" data-layout="button_count" data-size="large"></div></div>
						<div class="m-1"><div class="fb-save" data-uri="http://www.your-domain.com/your-page.html" data-size="large"></div></div>
						<div class="m-1"><a class="twitter-share-button" data-size="large" href="https://twitter.com/intent/tweet?text={{ \Illuminate\Support\Str::title($article->title) }}&via=Allrecipes&url={{ $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] }}">Tweet</a></div>
						<div class="m-1"><a class="block rounded-full bg-green-500 px-2.5 py-0.5 text-white hover:bg-green-600" target="_blank" href="https://wa.me/?text=Awesome%20Blog!%5Cn%20blog.shahednasser.com" data-action="share/whatsapp/share"><i class="fa-brands fa-whatsapp"></i> Share</a></div>
						<div class="m-1"><a href="https://www.pinterest.com/pin/create/button/" data-pin-tall="true" data-pin-do="buttonPin" media="{{ $article->image_url }}" description="{{ $article->title }}"> </a></div>
						<div class="m-1"><div class="snapchat-creative-kit-share" data-size="large"></div></div>
						<div class="m-1"><script type="IN/Share" data-url="{{ $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] }}"></script></div>
					</div>
				</section>
				<section class="py-2">
					@if ($article->comments && is_countable($article->comments) && $article->comments->count() > 0)
						<h2 class="py-2 text-2xl">Comments</h2>
						@foreach ($article->comments as $comment)
							<div class="my-2 flex">
								<div class="w-16">
									@if ($comment->user->photo_url && Illuminate\Support\Facades\Storage::exists($comment->user->photo_url))
										<img src="{{ $comment->user->photo_url }}" alt="" class="h-16 w-16 rounded-full p-2">
									@else
										<img src="/uploads/user.png" alt="" class="h-16 w-16 rounded-full p-2">
									@endif
								</div>
								<div class="flex-grow rounded border p-3 shadow">
									<h4 class="font-semibold">{{ \Illuminate\Support\Str::title($comment->user->firstname . " " . $comment->user->lastname) }}</h4>
									<p class="text-sm">{{ $comment->updated_at->diffForHumans() }}</p>
									<p class="py-3 pl-2">{{ $comment->body }}</p>
								</div>
							</div>
						@endforeach
					@else
						<h2 class="py-2 text-2xl">No Comments Yet </h2>
					@endif
				</section>
				<section class="my-5">
					<h4 class="py-2 text-2xl">Leave a Comment</h4>
					@auth
						<form method="POST" autocomplete="off" action="{{ route("comments.store", ["type" => "article", "id" => $article->id], false) }}">
							@csrf
							<div>
								<span class="inline-block rounded-t bg-neutral-200 px-2 py-1">as: <span class="font-bold">{{ \Illuminate\Support\Str::title(auth()->user()->firstname . " " . auth()->user()->lastname) }}</span></span>
							</div>
							<textarea required name="body" id="body" class="mb-2 block w-full border bg-neutral-100 p-2 text-sm font-medium outline-none" placeholder="Type your comment here..." rows="6"></textarea>
							<div class="flex py-2">
								<input type="submit" class="ml-auto cursor-pointer rounded bg-teal-500 px-5 py-2.5 text-sm font-bold text-white hover:bg-teal-600 active:bg-teal-500" value="Submit">
							</div>
						</form>
					@endauth
					@guest
						<div class="py-2 text-center text-lg">Login to comment</div>
					@endguest
				</section>
				<section class="py-2">
					<div class="flex items-center justify-center">
						<span class="h-1 flex-1 bg-neutral-600"></span>
						<span class="px-3 pb-1 text-xl font-medium">Check out other articles</span>
						<span class="h-1 flex-1 bg-neutral-600"></span>
					</div>
					<div class="grid gap-5 py-2 md:grid-cols-3">
						@foreach ($article->relatedArticles(3) as $relatedArticle)
							<article>
								<a class="block transition-all duration-700 hover:scale-105" href="{{ route("articles.show", ["slug" => $relatedArticle->slug], false) }}">
									<img src="{{ $relatedArticle->image_url }}" alt="{{ $relatedArticle->title }}" class="block h-64 w-full border object-cover p-1">
									<h4 class="py-1 text-center text-lg font-light">{{ $relatedArticle->title }}</h4>
								</a>
							</article>
						@endforeach
						<article>
							<a class="block transition-all duration-700 hover:scale-105" href="">
								<img src="https://images.immediate.co.uk/production/volatile/sites/30/2022/08/Cajun-chicken-one-pot-3f68774.jpg" alt="" class="block h-64 w-full border object-cover p-1">
								<h4 class="py-1 text-center text-lg font-light">Cajun chicken pasta</h4>
							</a>
						</article>
						<article>
							<a class="block transition-all duration-700 hover:scale-105" href="">
								<img src="https://images.immediate.co.uk/production/volatile/sites/30/2023/08/Chicken-tinga-tacos-4d3272f.jpg" alt="" class="block h-64 w-full border object-cover p-1">
								<h4 class="py-1 text-center text-lg font-light">Chicken tinga tacos</h4>
							</a>
						</article>
						<article>
							<a class="block transition-all duration-700 hover:scale-105" href="">
								<img src="https://images.immediate.co.uk/production/volatile/sites/30/2023/05/Next-level-buffalo-wings-56607c2.jpg  " alt="" class="block h-64 w-full border object-cover p-1">
								<h4 class="py-1 text-center text-lg font-light">Next level buffalo wings</h4>
							</a>
						</article>
					</div>
				</section>
			</main>
		</div>
		<div class="w-full md:w-1/3 md:pl-5">
			@include("inc.aside")
		</div>
	</div>
	@include("inc.footer")
</body>

</html>
