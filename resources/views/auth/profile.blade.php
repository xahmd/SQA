<!DOCTYPE html>
<html lang="en">

<head>
	<title>Profile | {{ env("APP_NAME", "Cuisine Compass") }}</title>
	@include("inc.common_head_tags")
	<script>
		class Router {
			constructor() {
				document.querySelectorAll("input[type='radio'][name='section']").forEach(input => {
					input.addEventListener('change', this.on_change)
				});
			}
			go_to(section_id = 1) {
				const input = document.querySelector("input[type='radio'][name='section']#section_" + section_id)
				input.checked = true
				input.dispatchEvent(new Event('change'))
			}
			on_change() {
				const section_id = document.querySelector("input[type='radio'][name='section']:checked")
					.value.substr(8);
				document.querySelectorAll("section[data-section]").forEach(section => {
					section.className = section.getAttribute('data-section') == section_id ? 'block' : 'hidden'
				});
			}
		}
		class Image_Upload_Previwer {
			constructor(input, img) {
				const form = input.closest("form");
				const submit_button_container = form.querySelector("#submit-button-container");
				form.onreset = function(event) {
					input.value = ""
					submit_button_container.classList.add('hidden')
					img.classList.add('hidden')
				}
				input.addEventListener("change", (event) => {
					//input = event.currentTarget
					if (input.files.length > 0) {
						const file = input.files[0]
						const reader = new FileReader()
						reader.readAsDataURL(file)
						reader.onload = function() {
							img.setAttribute('src', reader.result)
							img.classList.remove('hidden')
							submit_button_container.classList.remove('hidden')
						}
						reader.onerror = function(error) {
							console.log('Error: ', error);
							img.setAttribute('src', '')
							img.classList.add('hidden')
							submit_button_container.classList.add('hidden')
						}
					} else {
						img.setAttribute('src', '')
						img.classList.add('hidden')
						submit_button_container.classList.add('hidden')
					}
				})
			}
		}
	</script>
</head>

<body>
	@include("inc.header")
	<main class="mx-auto max-w-6xl py-10">
		<section class="flex items-center justify-center">
			<div class="relative mx-1">
				<input class="peer invisible absolute h-0 w-0 opacity-0" type="radio" name="section" id="section_1" value="section_1" checked>
				<label class="block cursor-pointer rounded bg-neutral-200 px-4 py-2 hover:bg-neutral-300 peer-checked:bg-amber-400" for="section_1">Section 1</label>
			</div>
			<div class="relative mx-1">
				<input class="peer invisible absolute h-0 w-0 opacity-0" type="radio" name="section" id="section_2" value="section_2">
				<label class="block cursor-pointer rounded bg-neutral-200 px-4 py-2 hover:bg-neutral-300 peer-checked:bg-amber-400" for="section_2">Section 2</label>
			</div>
			<div class="relative mx-1">
				<input class="peer invisible absolute h-0 w-0 opacity-0" type="radio" name="section" id="section_3" value="section_3">
				<label class="block cursor-pointer rounded bg-neutral-200 px-4 py-2 hover:bg-neutral-300 peer-checked:bg-amber-400" for="section_3">Section 3</label>
			</div>
		</section>
		@if (Session::has("error"))
			<section>
                <div class="text-center py-4 flex flex-col ">
                    <h4 class="text-red-500">{{ Session::get("error") }}</h4>
                </div>
			</section>
		@endif
		<section data-section="1">
			<div class="relative mb-4 mt-20 pt-20">
				<div class="absolute left-1/2 top-0 h-32 w-32 -translate-x-1/2 -translate-y-1/2 rounded-full">
					<form id="user_photo_form" enctype="multipart/form-data" method="POST" autocomplete="off" action="{{ route("auth.profile", [], false) }}">
						@csrf
						@if (auth()->user()->photo_url && \Illuminate\Support\Facades\Storage::disk("public")->exists(Str::replace("/uploads/", "", auth()->user()->photo_url)))
							<img onerror="this.classList.add('hidden')" class="absolute h-full w-full overflow-hidden rounded-full object-cover" src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->firstname . " " . auth()->user()->lastname }}">
						@else
							<img class="absolute h-full w-full overflow-hidden rounded-full bg-neutral-200 object-cover" src="/uploads/user.png" alt="{{ auth()->user()->firstname . " " . auth()->user()->lastname }}">
						@endif
						<img id="preview_user_photo" class="absolute hidden h-full w-full overflow-hidden rounded-full object-cover">
						<div class="group absolute h-full w-full overflow-hidden rounded-full">
							<label class="hidden h-full w-full cursor-pointer items-center justify-center overflow-hidden rounded-full bg-black bg-opacity-30 group-hover:flex" for="image"><i class="fa-solid fa-image text-2xl text-neutral-200"></i></label>
							<input type="file" id="image" name="image" class="hidden">
						</div>
						<div id="submit-button-container" class="absolute left-0 top-full hidden w-full pt-2 text-center">
							<button type="reset" class="rounded bg-red-300 px-2 py-1 text-sm font-bold hover:bg-red-200">X</button>
							<button type="submit" class="rounded bg-teal-300 px-2.5 py-1 text-sm font-bold hover:bg-teal-200">Submit</button>
						</div>
					</form>
				</div>
				<div class="grid sm:grid-cols-2">
					<form method="POST" autocomplete="off" action="{{ route("auth.profile", [], false) }}" class="p-2">
						@csrf
						<h2 class="py-1 text-center text-2xl">Your Informations</h2>
						<input value="{{ auth()->user()->firstname }}" class="my-2 w-full rounded border border-neutral-200 bg-neutral-200 p-2 leading-8 outline-none hover:border-neutral-100 hover:bg-neutral-100 focus:bg-white" type="text" name="firstname" id="firstname" placeholder="First Name">
						<input value="{{ auth()->user()->lastname }}" class="my-2 w-full rounded border border-neutral-200 bg-neutral-200 p-2 leading-8 outline-none hover:border-neutral-100 hover:bg-neutral-100 focus:bg-white" type="text" name="lastname" id="lastname" placeholder="Last Name">
						<input value="{{ auth()->user()->username }}" class="my-2 w-full rounded border border-neutral-200 bg-neutral-200 p-2 leading-8 outline-none hover:border-neutral-100 hover:bg-neutral-100 focus:bg-white" type="text" name="username" id="username" placeholder="Username" pattern="/^[a-z][a-z\d\_]+$/i" onkeyup="this.value = this.value.toLowerCase()">
						<input value="{{ auth()->user()->email }}" class="my-2 w-full rounded border border-neutral-200 bg-neutral-200 p-2 leading-8 outline-none hover:border-neutral-100 hover:bg-neutral-100 focus:bg-white" type="email" name="email" id="email" placeholder="Email">
						<button type="submit" class="mx-auto my-1 block w-fit rounded bg-teal-400 px-5 py-2 hover:bg-teal-300">Save</button>
					</form>
					<form method="POST" autocomplete="off" action="{{ route("auth.profile", [], false) }}" class="p-2">
						@csrf
						<h2 class="py-1 text-center text-2xl">Your Password</h2>
						<input class="my-2 w-full rounded border border-neutral-200 bg-neutral-200 p-2 leading-8 outline-none hover:border-neutral-100 hover:bg-neutral-100 focus:bg-white" type="password" name="old_password" id="old_password" placeholder="Old Password">
						<input class="my-2 w-full rounded border border-neutral-200 bg-neutral-200 p-2 leading-8 outline-none hover:border-neutral-100 hover:bg-neutral-100 focus:bg-white" type="password" name="new_password" id="new_password" placeholder="New Password">
						<input class="my-2 w-full rounded border border-neutral-200 bg-neutral-200 p-2 leading-8 outline-none hover:border-neutral-100 hover:bg-neutral-100 focus:bg-white" type="password" name="new_password_confirmation" id="new_password_confirmation" placeholder="New Password Confirmation">
						<button type="submit" class="mx-auto my-1 block w-fit rounded bg-teal-400 px-5 py-2 hover:bg-teal-300">Change Password</button>
					</form>
				</div>
			</div>
		</section>
		<section data-section="2">
		</section>
		<section data-section="3">
		</section>
		<script>
			new Router()
			const temp = new Image_Upload_Previwer(
				document.querySelector("#user_photo_form input[type='file']"),
				document.querySelector("img#preview_user_photo")
			)
		</script>
	</main>
	@include("inc.footer")
</body>

</html>
