<aside class="w-20 md:w-64 h-screen sticky top-0 flex flex-col p-4 space-y-4 bg-black text-white border-r border-dark-border z-20" x-data="postModal" @keydown.escape="postModalOpen = false">
    <!-- Logo -->
    <div class="p-2 mb-6">
        <a href="{{ route('user.home') }}" class="flex items-center justify-center md:justify-start">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-primary">
                <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path>
            </svg>
            <span class="ml-3 text-xl font-bold hidden md:inline">X-Social</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex flex-col space-y-1">
        <a href="{{ route('user.home') }}" class="{{ request()->routeIs('user.home') ? 'bg-dark-lighter text-primary' : 'text-gray-300 hover:bg-dark-hover hover:text-white' }} flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 group">
            <div class="flex items-center justify-center w-10 h-10 rounded-full group-hover:bg-opacity-10 group-hover:bg-white transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
            <span class="text-lg font-medium hidden md:inline">Accueil</span>
        </a>
        <a href="{{ route('user.explore') }}" class="{{ request()->routeIs('user.explore') ? 'bg-dark-lighter text-primary' : 'text-gray-300 hover:bg-dark-hover hover:text-white' }} flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 group">
            <div class="flex items-center justify-center w-10 h-10 rounded-full group-hover:bg-opacity-10 group-hover:bg-white transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                </svg>
            </div>
            <span class="text-lg font-medium hidden md:inline">Explorer</span>
        </a>
        <a href="{{ route('user.notifications') }}" class="{{ request()->routeIs('user.notifications') ? 'bg-dark-lighter text-primary' : 'text-gray-300 hover:bg-dark-hover hover:text-white' }} flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 group">
            <div class="flex items-center justify-center w-10 h-10 rounded-full group-hover:bg-opacity-10 group-hover:bg-white transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </div>
            <span class="text-lg font-medium hidden md:inline">Notifications</span>
        </a>
        <a href="{{ route('user.profile') }}" class="{{ request()->routeIs('user.profile') ? 'bg-dark-lighter text-primary' : 'text-gray-300 hover:bg-dark-hover hover:text-white' }} flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 group">
            <div class="flex items-center justify-center w-10 h-10 rounded-full group-hover:bg-opacity-10 group-hover:bg-white transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <span class="text-lg font-medium hidden md:inline">Profile</span>
        </a>
    </nav>

    <!-- Post Button -->
    <div class="mt-6">
        <button @click="postModalOpen = true" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 px-4 rounded-full transition-colors duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-primary/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="hidden md:inline text-base">Post</span>
        </button>
    </div>

    <!-- Profile -->
    <div class="mt-auto relative profile-container">
        <div class="flex items-center space-x-3 p-3 rounded-xl hover:bg-dark-hover cursor-pointer transition-all duration-200" onclick="toggleDropdown()">
            <img src="{{ Auth::user()->profile_picture ? Storage::url(Auth::user()->profile_picture) : 'https://i.pravatar.cc/100' }}" alt="Avatar" class="w-10 h-10 rounded-full border-2 border-dark-border">
            <div class="hidden md:block overflow-hidden">
                <p class="font-bold text-sm truncate">{{ Auth::user()->name }}</p>
                <p class="text-gray-400 text-sm truncate">{{'@' . Auth::user()->username }}</p>
            </div>
            <div class="hidden md:block ml-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-dark-lighter rounded-lg shadow-lg z-10">
            <a href="{{ route('user.settings') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-dark-hover hover:text-white rounded-t-lg">Settings</a>
            <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-dark-hover hover:text-white rounded-b-lg" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>

    <!-- Background Overlay -->
    <div class="fixed inset-0 z-40 bg-black/70" x-show="postModalOpen" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <!-- Post Creation Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-show="postModalOpen" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-init="initModal">
        <div class="relative bg-dark-lighter w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden" @click.away="postModalOpen = false">
            <div class="bg-gradient-to-r from-primary/20 to-secondary/20 h-2"></div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-4">
                    <button @click="postModalOpen = false" class="text-white hover:bg-dark-hover p-2 rounded-full transition-colors">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                    <div class="text-lg font-bold text-gray-300">Create Post</div>
                    <div class="w-8"></div>
                </div>
                <form id="postForm" class="flex flex-col">
                    @csrf
                    <div class="flex gap-4 mb-4">
                        <img src="{{ Auth::user()->profile_picture ? Storage::url(Auth::user()->profile_picture) : asset('images/default-profile.png') }}" alt="Profile Picture" class="w-10 h-10 rounded-full ring-2 ring-primary/40">
                        <div class="flex-1">
                            <textarea name="content" rows="4" placeholder="What are you thinking?" class="w-full bg-transparent text-white border-0 focus:outline-none focus:ring-0 resize-none text-lg placeholder-gray-500"></textarea>
                            <span id="content-error" class="text-red-500 text-sm hidden mt-1"></span>
                        </div>
                    </div>
                    <div id="mediaPreviewContainer" class="hidden mt-2 mb-4 bg-dark-hover/50 rounded-2xl p-2 border border-dark-border/50 max-h-64 overflow-y-auto"></div>
                    <div class="flex justify-between items-center border-t border-dark-border/70 pt-4">
                        <div class="flex gap-4 text-primary">
                            <label class="cursor-pointer hover:bg-dark-hover p-2 rounded-full transition-colors flex items-center justify-center">
                                <i class="fa-regular fa-image text-xl"></i>
                                <input type="file" name="media[]" class="hidden" accept="image/*,video/mp4" multiple>
                            </label>
                        </div>
                        <button type="submit" id="submitPost" class="bg-primary text-white font-bold px-6 py-2 rounded-full hover:bg-secondary transition-colors disabled:bg-gray-700 disabled:text-gray-400 text-base shadow-lg" disabled>Post</button>
                    </div>
                </form>
                <button type="button" id="clearMedia" class="hidden text-red-400 hover:text-red-500 transition-colors text-sm font-medium mt-2">Remove All</button>
            </div>
        </div>
    </div>

    <script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdownMenu');
        dropdown.classList.toggle('hidden');
    }

    document.addEventListener('click', (event) => {
        const dropdown = document.getElementById('dropdownMenu');
        const profile = document.querySelector('.profile-container');
        if (dropdown && !dropdown.contains(event.target) && (!profile || !profile.contains(event.target))) {
            dropdown.classList.add('hidden');
        }
    });

    document.addEventListener('alpine:init', () => {
        Alpine.data('postModal', () => ({
            postModalOpen: false,
            initModal() {
                const form = document.getElementById('postForm');
                const submitBtn = document.getElementById('submitPost');
                const contentInput = form.querySelector('textarea[name="content"]');
                const mediaInput = form.querySelector('input[name="media[]"]');
                const previewContainer = document.getElementById('mediaPreviewContainer');
                const clearMediaBtn = document.getElementById('clearMedia');

                const updateSubmitButton = () => {
                    const hasContent = contentInput.value.trim().length > 0;
                    const hasMedia = mediaInput.files && mediaInput.files.length > 0;
                    submitBtn.disabled = !(hasContent || hasMedia);
                };

                contentInput.addEventListener('input', updateSubmitButton);

                mediaInput.addEventListener('change', () => {
                    console.log('Media input changed, files:', mediaInput.files);
                    previewContainer.innerHTML = '';
                    if (mediaInput.files && mediaInput.files.length > 0) {
                        if (mediaInput.files.length > 4) {
                            alert('Max 4 files allowed.');
                            mediaInput.value = '';
                            return;
                        }
                        previewContainer.classList.remove('hidden');
                        clearMediaBtn.classList.remove('hidden');
                        Array.from(mediaInput.files).forEach(file => {
                            const fileType = file.type;
                            console.log('Processing file:', file.name, fileType);
                            const previewDiv = document.createElement('div');
                            previewDiv.className = 'relative rounded-xl overflow-hidden bg-black/40 mb-2';
                            if (fileType.startsWith('image/')) {
                                const img = document.createElement('img');
                                img.className = 'max-h-32 w-full object-contain';
                                img.alt = 'Preview';
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    console.log('Image loaded for:', file.name);
                                    img.src = e.target.result;
                                };
                                reader.readAsDataURL(file);
                                previewDiv.appendChild(img);
                            } else if (fileType.startsWith('video/')) {
                                const video = document.createElement('video');
                                video.className = 'max-h-32 w-full object-contain';
                                video.controls = true;
                                video.src = URL.createObjectURL(file);
                                console.log('Video URL set for:', file.name);
                                previewDiv.appendChild(video);
                            }
                            const filenameP = document.createElement('p');
                            filenameP.className = 'text-xs text-gray-500 mt-1 truncate';
                            filenameP.textContent = file.name;
                            previewDiv.appendChild(filenameP); // Fixed: append filenameP, not previewDiv
                            previewContainer.appendChild(previewDiv);
                        });
                    } else {
                        previewContainer.classList.add('hidden');
                        clearMediaBtn.classList.add('hidden');
                    }
                    updateSubmitButton();
                });

                clearMediaBtn.addEventListener('click', () => {
                    mediaInput.value = '';
                    previewContainer.innerHTML = '';
                    previewContainer.classList.add('hidden');
                    clearMediaBtn.classList.add('hidden');
                    updateSubmitButton();
                });

                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    submitBtn.disabled = true;
                    console.log('Form submitting from:', window.location.pathname);

                    const errorSpans = ['content', 'media'].map(field => document.getElementById(`${field}-error`));
                    errorSpans.forEach(span => span && (span.classList.add('hidden'), span.textContent = ''));

                    try {
                        const formData = new FormData(form);
                        console.log('Form data:', Array.from(formData.entries()));
                        const response = await fetch('{{ route('posts.store') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        console.log('Response status:', response.status);
                        const text = await response.text();
                        console.log('Response text:', text);

                        if (!response.ok) {
                            try {
                                const error = JSON.parse(text);
                                throw error;
                            } catch {
                                throw new Error('Non-JSON error: ' + text);
                            }
                        }

                        const data = JSON.parse(text);
                        console.log('Parsed response:', data);

                        if (data.success) {
                            form.reset();
                            previewContainer.innerHTML = '';
                            previewContainer.classList.add('hidden');
                            clearMediaBtn.classList.add('hidden');
                            this.postModalOpen = false;

                            console.log('Calling appendNewPost with:', data.post);
                            if (typeof window.appendNewPost === 'function') {
                                window.appendNewPost(data.post);
                            } else {
                                console.log('appendNewPost not found on this page');
                            }

                            showToast('Post created successfully!', data.post.id);
                        } else if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const errorSpan = document.getElementById(`${field}-error`);
                                if (errorSpan) {
                                    errorSpan.textContent = data.errors[field][0];
                                    errorSpan.classList.remove('hidden');
                                }
                            });
                        }
                    } catch (error) {
                        console.error('Submission error:', error);
                        if (error.errors) {
                            Object.keys(error.errors).forEach(field => {
                                const errorSpan = document.getElementById(`${field}-error`);
                                if (errorSpan) {
                                    errorSpan.textContent = error.errors[field][0];
                                    errorSpan.classList.remove('hidden');
                                }
                            });
                        } else {
                            console.error('Detailed error:', error.message);
                            alert('Submission failed: ' + error.message);
                        }
                    } finally {
                        submitBtn.disabled = false;
                    }
                });

                updateSubmitButton();
            }
        }));
    });

    function showToast(message, postId) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center gap-2';
        toast.innerHTML = `
            ${message}
            <a href="/posts/${postId}" class="text-white underline hover:text-gray-200">View</a>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    }
    </script>
</aside>