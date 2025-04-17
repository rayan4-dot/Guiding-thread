// resources/js/fetch.js
document.addEventListener('DOMContentLoaded', () => {
    console.log('fetch.js loaded and running!');

    const currentUserId = window.currentUserId || null;

    // Modal elements
    const openModalBtn = document.getElementById('openPostModal');
    const postModal = document.getElementById('postModal');
    const postModalOverlay = document.getElementById('postModalOverlay');
    const closeModalBtn = document.getElementById('closePostModal');
    const postForm = document.getElementById('postForm');
    const submitBtn = document.getElementById('submitPost');
    const contentInput = postForm.querySelector('textarea[name="content"]');
    const mediaInput = document.getElementById('mediaInput');
    const previewContainer = document.getElementById('mediaPreviewContainer');
    const clearMediaBtn = document.getElementById('clearMedia');

    // Modal toggle functions
    const openModal = () => {
        postModal.classList.remove('hidden');
        postModalOverlay.classList.remove('hidden');
    };

    const closeModal = () => {
        postModal.classList.add('hidden');
        postModalOverlay.classList.add('hidden');
        postForm.reset();
        previewContainer.innerHTML = '';
        previewContainer.classList.add('hidden');
        clearMediaBtn.classList.add('hidden');
        submitBtn.disabled = true;
    };

    // Event listeners for modal
    if (openModalBtn) {
        openModalBtn.addEventListener('click', openModal);
    }
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }
    if (postModalOverlay) {
        postModalOverlay.addEventListener('click', closeModal);
    }
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !postModal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Form handling
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

    postForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        submitBtn.disabled = true;
        console.log('Form submitting from:', window.location.pathname);

        const errorSpans = ['content', 'media'].map(field => document.getElementById(`${field}-error`));
        errorSpans.forEach(span => span && (span.classList.add('hidden'), span.textContent = ''));

        try {
            const formData = new FormData(postForm);
            console.log('Form data:', Array.from(formData.entries()));
            const response = await fetch(postForm.action, {
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
                postForm.reset();
                previewContainer.innerHTML = '';
                previewContainer.classList.add('hidden');
                clearMediaBtn.classList.add('hidden');
                closeModal();

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

    // Dropdown toggle for profile menu
    window.toggleDropdown = () => {
        const dropdown = document.getElementById('dropdownMenu');
        if (dropdown) dropdown.classList.toggle('hidden');
    };

    // Close dropdown on outside click
    document.addEventListener('click', (event) => {
        const dropdown = document.getElementById('dropdownMenu');
        const profile = document.querySelector('.profile-container');
        if (dropdown && !dropdown.contains(event.target) && (!profile || !profile.contains(event.target))) {
            dropdown.classList.add('hidden');
        }
    });

    // Delete post
    window.deletePost = async (postId) => {
        console.log('deletePost called with ID:', postId);
        try {
            const response = await fetch(`/post/${postId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();
            console.log('Server response:', data);

            if (data.success) {
                const postElement = document.getElementById(`post-${postId}`);
                if (postElement) {
                    postElement.remove();
                    console.log(`Post ${postId} removed from DOM`);
                } else {
                    console.warn(`Post ${postId} not found in DOM`);
                }

                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-4 py-2 rounded-full shadow-lg z-50 flex items-center';
                toast.innerHTML = '<i class="fa-solid fa-check-circle text-green-500 mr-2"></i> Post deleted successfully';
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.remove();
                    console.log('Toast removed');
                    window.location.href = data.redirect;
                }, 1000);
            } else {
                console.error('Failed to delete post:', data.message);
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-4 py-2 rounded-full shadow-lg z-50 flex items-center';
                toast.innerHTML = '<i class="fa-solid fa-exclamation-circle text-red-500 mr-2"></i> Failed to delete post';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }
        } catch (error) {
            console.error('Error during deletion:', error);
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-4 py-2 rounded-full shadow-lg z-50 flex items-center';
            toast.innerHTML = '<i class="fa-solid fa-exclamation-circle text-red-500 mr-2"></i> Error deleting post';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    };

    // Append new post
    window.appendNewPost = (post, isOwnerOverride = null) => {
        console.log('Appending post:', post);
        const container = document.getElementById('posts-container');
        if (!container) {
            console.error('Posts container not found!');
            return;
        }

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
            <div class="relative">
                <button onclick="document.getElementById('options-${post.id}').classList.toggle('hidden')" class="p-2 rounded-full hover:bg-zinc-800 transition-colors">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
                <div id="options-${post.id}" class="hidden absolute right-0 mt-1 w-48 bg-zinc-900 border border-zinc-700 rounded-lg shadow-xl z-10">
                    <button onclick="deletePost(${post.id}); document.getElementById('options-${post.id}').classList.add('hidden')" class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-zinc-800 rounded-lg">
                        <i class="fa-solid fa-trash-can w-5"></i>
                        <span>Delete Post</span>
                    </button>
                </div>
            </div>
        ` : `
            <div class="relative">
                <button onclick="document.getElementById('options-${post.id}').classList.toggle('hidden')" class="p-2 rounded-full hover:bg-zinc-800 transition-colors">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
                <div id="options-${post.id}" class="hidden absolute right-0 mt-1 w-48 bg-zinc-900 border border-zinc-700 rounded-lg shadow-xl z-10">
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
                            <a href="/post/${post.id}?nocache=${Math.random()}" class="block text-primary hover:underline text-sm mt-2">View Post</a>
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

        const newPost = document.getElementById(`post-${post.id}`);
        if (newPost) {
            newPost.querySelectorAll('img[data-media]').forEach(img => {
                img.addEventListener('click', (e) => {
                    e.preventDefault();
                    const mediaModal = document.getElementById('mediaModal');
                    const mediaModalImg = mediaModal.querySelector('img');
                    const mediaModalVideo = mediaModal.querySelector('video');
                    if (img.dataset.type === 'image') {
                        mediaModalImg.src = img.dataset.media;
                        mediaModalImg.classList.remove('hidden');
                        mediaModalVideo.classList.add('hidden');
                    } else {
                        mediaModalVideo.querySelector('source').src = img.dataset.media;
                        mediaModalVideo.classList.remove('hidden');
                        mediaModalImg.classList.add('hidden');
                    }
                    mediaModal.classList.remove('hidden');
                });
            });
        }
    };

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