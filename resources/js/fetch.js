document.addEventListener('DOMContentLoaded', () => {
    if (typeof Alpine === 'undefined') {
        console.error('Alpine.js is not loaded!');
        return;
    }

    // Get the current user ID from a global variable (set in the Blade template or layout)
    const currentUserId = window.currentUserId || null;

    // Dropdown toggle for user sidebar
    window.toggleDropdown = () => {
        const dropdown = document.getElementById('dropdownMenu');
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    };

    // Close dropdown when clicking outside
    document.addEventListener('click', (event) => {
        const dropdown = document.getElementById('dropdownMenu');
        const profile = document.querySelector('.profile-container');
        if (dropdown && !dropdown.contains(event.target) && (!profile || !profile.contains(event.target))) {
            dropdown.classList.add('hidden');
        }
    });

    // Delete Post Function (used in all three pages)
    window.deletePost = async (postId, redirectAfterDelete = false) => {
        if (confirm('Are you sure you want to delete this post?')) {
            try {
                const response = await fetch(`/post/${postId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (data.success) {
                    const postElement = document.getElementById(`post-${postId}`);
                    if (postElement) {
                        postElement.remove();
                    }

                    // Show success toast notification
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-4 py-2 rounded-full shadow-lg z-50 flex items-center';
                    toast.innerHTML = '<i class="fa-solid fa-check-circle text-green-500 mr-2"></i> Post deleted successfully';
                    document.body.appendChild(toast);

                    // Handle redirect (used in post.blade.php)
                    setTimeout(() => {
                        toast.remove();
                        if (redirectAfterDelete) {
                            window.location.href = '/home';
                        }
                    }, 2000);
                } else {
                    console.error('Failed to delete post:', data.message);
                    // Show error toast
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-4 py-2 rounded-full shadow-lg z-50 flex items-center';
                    toast.innerHTML = '<i class="fa-solid fa-exclamation-circle text-red-500 mr-2"></i> Failed to delete post';
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 3000);
                }
            } catch (error) {
                console.error('Error during deletion:', error);
                // Show error toast
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-4 py-2 rounded-full shadow-lg z-50 flex items-center';
                toast.innerHTML = '<i class="fa-solid fa-exclamation-circle text-red-500 mr-2"></i> Error deleting post';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }
        }
    };

    // Append New Post Function (used in home.blade.php and profile.blade.php)
    window.appendNewPost = (post, isOwnerOverride = null) => {
        console.log('Appending post:', post);
        const container = document.getElementById('posts-container');
        if (!container) {
            console.error('Posts container not found!');
            return;
        }

        // Determine if the post belongs to the current user
        const isOwner = isOwnerOverride !== null ? isOwnerOverride : (post.user && post.user.id ? Number(post.user.id) === currentUserId : true);

        const youtubePattern = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/embed\/)([^"&?\/\s]{11})/i;
        const isYoutubeContent = youtubePattern.test(post.content);
        const videoIdContent = isYoutubeContent ? post.content.match(youtubePattern)[1] : null;
        const isYoutubeShared = post.shared_link && youtubePattern.test(post.shared_link);
        const videoIdShared = isYoutubeShared ? post.shared_link.match(youtubePattern)[1] : null;
        const contentWithoutUrl = videoIdContent ? post.content.replace(youtubePattern, '').trim() : post.content;

        const mediaHtml = Array.isArray(post.media_path) && post.media_path.length > 0 ? `
            <div class="grid ${post.media_path.length === 1 ? 'grid-cols-1' : 'grid-cols-2'} gap-2 rounded-xl overflow-hidden mb-3">
                ${post.media_path.map(media => `
                    ${media.type === 'image' ? `
                        <img src="${media.path}" alt="Post image" class="w-full h-auto max-h-[500px] object-cover rounded-xl hover:brightness-90 transition-all duration-200 cursor-pointer" data-media="${media.path}" data-type="image">
                    ` : `
                        <video controls class="w-full h-auto max-h-[500px] object-cover rounded-xl">
                            <source src="${media.path}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    `}
                `).join('')}
            </div>
        ` : videoIdContent && !post.media_path && !post.shared_link ? `
            <div class="mb-3 flex justify-center">
                <iframe class="w-full max-w-2xl h-64 rounded-xl" src="https://www.youtube.com/embed/${videoIdContent}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        ` : post.shared_link ? `
            ${videoIdShared ? `
                <div class="mb-3 flex justify-center">
                    <iframe class="w-full max-w-2xl h-64 rounded-xl" src="https://www.youtube.com/embed/${videoIdShared}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            ` : `
                <div class="p-3 border border-dark-border rounded-xl hover:bg-dark-hover/30 transition-all mb-3">
                    <span class="text-primary hover:underline line-clamp-1">${post.shared_link}</span>
                </div>
            `}
        ` : '';

        const dropdownHtml = isOwner ? `
            <div class="relative" x-data="{ showOptions: false }" @click.away="showOptions = false">
                <button @click="showOptions = !showOptions" class="p-2 rounded-full hover:bg-zinc-800 transition-colors">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
                <div x-show="showOptions" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute right-0 mt-1 w-48 bg-zinc-900 border border-zinc-700 rounded-lg shadow-xl z-10">
                    <a href="/post/${post.id}/edit" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-zinc-800 rounded-t-lg">
                        <i class="fa-solid fa-pen-to-square w-5"></i>
                        <span>Edit Post</span>
                    </a>
                    <button @click="showOptions = false; deletePost(${post.id})" class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-zinc-800 rounded-b-lg">
                        <i class="fa-solid fa-trash-can w-5"></i>
                        <span>Delete Post</span>
                    </button>
                </div>
            </div>
        ` : `
            <div class="relative" x-data="{ showOptions: false }" @click.away="showOptions = false">
                <button @click="showOptions = !showOptions" class="p-2 rounded-full hover:bg-zinc-800 transition-colors">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
                <div x-show="showOptions" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute right-0 mt-1 w-48 bg-zinc-900 border border-zinc-700 rounded-lg shadow-xl z-10">
                    <button class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm hover:bg-zinc-800 rounded-lg">
                        <i class="fa-solid fa-flag w-5"></i>
                        <span>Report Post</span>
                    </button>
                </div>
            </div>
        `;

        const postHtml = `
            <article class="p-4 border-b border-dark-border" id="post-${post.id}">
                <div class="flex gap-4">
                    <a href="/profile/${post.user.username}" class="flex-shrink-0">
                        <img src="${post.user.profile_picture || '/images/default-profile.png'}" alt="${post.user.name}" class="w-12 h-12 rounded-full object-cover hover:opacity-90 transition-opacity">
                    </a>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <a href="/profile/${post.user.username}" class="font-bold hover:underline cursor-pointer">${post.user.name}</a>
                            <span class="text-gray-500">@${post.user.username}</span>
                            <span class="text-gray-500">·</span>
                            <time class="text-gray-500 hover:underline cursor-pointer">${post.created_at}</time>
                            ${dropdownHtml}
                        </div>
                        <div class="block">
                            ${contentWithoutUrl ? `<p class="mb-3 text-[15px] leading-relaxed">${contentWithoutUrl.replace(/\n/g, '<br>')}</p>` : ''}
                            ${mediaHtml}
                            <a href="/post/${post.id}" class="block text-primary hover:underline text-sm mt-2">View Post</a>
                        </div>
                        <div class="flex justify-start gap-8">
                            <button class="flex items-center gap-2 hover:text-blue-500 transition-colors group" aria-label="Comments">
                                <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                    <i class="fa-regular fa-comment"></i>
                                </div>
                                <span>0</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-red-500 transition-colors group" aria-label="Like">
                                <div class="p-2 rounded-full group-hover:bg-red-500/10 transition-colors">
                                    <i class="fa-regular fa-heart"></i>
                                </div>
                                <span>0</span>
                            </button>
                        </div>
                    </div>
                </div>
            </article>
        `;
        container.insertAdjacentHTML('afterbegin', postHtml);

        // Add event listeners for media modal (used in home.blade.php)
        const newPost = document.getElementById(`post-${post.id}`);
        if (newPost) {
            newPost.querySelectorAll('img[data-media]').forEach(img => {
                img.addEventListener('click', (e) => {
                    e.preventDefault();
                    const alpineData = container.__x.$data;
                    alpineData.mediaModalOpen = true;
                    alpineData.selectedMedia = img.dataset.media;
                    alpineData.selectedMediaType = img.dataset.type;
                });
            });
        }
    };

    // Show Toast Function (used in post creation modal)
    window.showToast = (message, postId) => {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center gap-2';
        toast.innerHTML = `
            ${message}
            <a href="/post/${postId}" class="text-white underline hover:text-gray-200">View</a>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    };
});

// Post Creation Modal Logic (from side-user.blade.php)
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
                        previewDiv.appendChild(filenameP);
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
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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

                        window.showToast('Post created successfully!', data.post.id);
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