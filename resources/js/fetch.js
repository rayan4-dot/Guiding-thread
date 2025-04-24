document.addEventListener('DOMContentLoaded', () => {
    console.log('fetch.js loaded and running!');
    const currentUserId = window.currentUserId || null;

    const postModal = document.getElementById('postModal');
    if (postModal) {
        const openModalBtn = document.getElementById('openPostModal');
        const postModalOverlay = document.getElementById('postModalOverlay');
        const closeModalBtn = document.getElementById('closePostModal');
        const postForm = document.getElementById('postForm');
        const submitBtn = document.getElementById('submitPost');
        const contentInput = postForm?.querySelector('textarea[name="content"]');
        const mediaInput = document.getElementById('mediaInput');
        const previewContainer = document.getElementById('mediaPreviewContainer');
        const clearMediaBtn = document.getElementById('clearMedia');

        const openModal = () => {
            postModal.classList.remove('hidden');
            postModalOverlay.classList.remove('hidden');
        };

        const closeModal = () => {
            postModal.classList.add('hidden');
            postModalOverlay.classList.add('hidden');
            postForm?.reset();
            if (previewContainer) {
                previewContainer.innerHTML = '';
                previewContainer.classList.add('hidden');
            }
            if (clearMediaBtn) clearMediaBtn.classList.add('hidden');
            if (submitBtn) submitBtn.disabled = true;
        };

        if (openModalBtn) openModalBtn.addEventListener('click', openModal);
        if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
        if (postModalOverlay) postModalOverlay.addEventListener('click', closeModal);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !postModal.classList.contains('hidden')) {
                closeModal();
            }
        });

        const updateSubmitButton = () => {
            if (contentInput && submitBtn) {
                const hasContent = contentInput.value.trim().length > 0;
                const hasMedia = mediaInput?.files?.length > 0;
                submitBtn.disabled = !(hasContent || hasMedia);
            }
        };

        if (contentInput) contentInput.addEventListener('input', updateSubmitButton);

        if (mediaInput) {
            mediaInput.addEventListener('change', () => {
                console.log('Media input changed, files:', mediaInput.files);
                if (previewContainer) previewContainer.innerHTML = '';
                if (mediaInput.files?.length > 0) {
                    if (mediaInput.files.length > 4) {
                        alert('Max 4 files allowed.');
                        mediaInput.value = '';
                        return;
                    }
                    if (previewContainer) previewContainer.classList.remove('hidden');
                    if (clearMediaBtn) clearMediaBtn.classList.remove('hidden');
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
                                img.src = e.target.result;
                            };
                            reader.readAsDataURL(file);
                            previewDiv.appendChild(img);
                        } else if (fileType.startsWith('video/')) {
                            const video = document.createElement('video');
                            video.className = 'max-h-32 w-full object-contain';
                            video.controls = true;
                            video.src = URL.createObjectURL(file);
                            previewDiv.appendChild(video);
                        }
                        const filenameP = document.createElement('p');
                        filenameP.className = 'text-xs text-gray-500 mt-1 truncate';
                        filenameP.textContent = file.name;
                        previewDiv.appendChild(filenameP);
                        if (previewContainer) previewContainer.appendChild(previewDiv);
                    });
                } else {
                    if (previewContainer) previewContainer.classList.add('hidden');
                    if (clearMediaBtn) clearMediaBtn.classList.add('hidden');
                }
                updateSubmitButton();
            });
        }

        if (clearMediaBtn) {
            clearMediaBtn.addEventListener('click', () => {
                mediaInput.value = '';
                if (previewContainer) {
                    previewContainer.innerHTML = '';
                    previewContainer.classList.add('hidden');
                }
                clearMediaBtn.classList.add('hidden');
                updateSubmitButton();
            });
        }

        if (postForm) {
            postForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (submitBtn) submitBtn.disabled = true;
                console.log('Form submitting from:', window.location.pathname);

                const errorSpans = ['content', 'media'].map(field => document.getElementById(`${field}-error`));
                errorSpans.forEach(span => span && (span.classList.add('hidden'), span.textContent = ''));

                try {
                    const formData = new FormData(postForm);
                    const response = await fetch(postForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        const text = await response.text();
                        try {
                            const error = JSON.parse(text);
                            throw error;
                        } catch {
                            throw new Error('Non-JSON error: ' + text);
                        }
                    }

                    const data = await response.json();
                    if (data.success) {
                        postForm.reset();
                        if (previewContainer) {
                            previewContainer.innerHTML = '';
                            previewContainer.classList.add('hidden');
                        }
                        if (clearMediaBtn) clearMediaBtn.classList.add('hidden');
                        closeModal();
                        if (typeof window.appendNewPost === 'function') {
                            window.appendNewPost(data.post);
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
                        window.showToast('Submission failed: ' + error.message);
                    }
                } finally {
                    if (submitBtn) submitBtn.disabled = false;
                }
            });
        }
    }


    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', async (event) => {
            event.preventDefault();
            const postId = button.dataset.postId;
            let isLiked = button.dataset.liked === 'true';
            const icon = button.querySelector('i');
            const countSpan = button.querySelector('.like-count');
            const currentCount = parseInt(countSpan.textContent);


            isLiked = !isLiked;
            button.dataset.liked = isLiked;
            countSpan.textContent = isLiked ? currentCount + 1 : currentCount - 1;
            icon.classList.toggle('far', !isLiked);
            icon.classList.toggle('fas', isLiked);
            icon.classList.toggle('text-red-500', isLiked);

            try {
                const response = await fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
            } catch (error) {
                console.error('Like error:', error);

                isLiked = !isLiked;
                button.dataset.liked = isLiked;
                countSpan.textContent = isLiked ? currentCount + 1 : currentCount;
                icon.classList.toggle('far', !isLiked);
                icon.classList.toggle('fas', isLiked);
                icon.classList.toggle('text-red-500', isLiked);
                window.showToast('Failed to toggle like');
            }
        });
    });


    window.deletePost = async (postId) => {
        try {
            const response = await fetch(`/post/${postId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            if (data.success) {
                const postElement = document.getElementById(`post-${postId}`);
                if (postElement) postElement.remove();
                window.showToast('Post deleted successfully');
            } else {
                window.showToast('Failed to delete post');
            }
        } catch (error) {
            console.error('Delete error:', error);
            window.showToast('Error deleting post');
        }
    };


    window.appendNewPost = (post) => {
        const container = document.getElementById('posts-container');
        if (!container) return;

        const isOwner = post.user?.id ? Number(post.user.id) === currentUserId : true;
        const youtubePattern = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i;
        const isYoutubeContent = youtubePattern.test(post.content);
        const videoIdContent = isYoutubeContent ? post.content.match(youtubePattern)[1] : null;
        const contentWithoutUrl = videoIdContent ? post.content.replace(youtubePattern, '').trim() : post.content;

        const mediaHtml = Array.isArray(post.media_path) && post.media_path.length > 0 ? `
            <div class="grid ${post.media_path.length === 1 ? 'grid-cols-1' : 'grid-cols-2'} gap-2 rounded-xl overflow-hidden mb-3">
                ${post.media_path.map(media => media.type === 'image' ? `
                    <img src="${media.path}" alt="Post image" class="w-full h-auto max-h-[500px] object-cover rounded-xl hover:brightness-90 transition-all duration-200 cursor-pointer" @click="mediaModalOpen = true; selectedMedia = '${media.path}'; selectedMediaType = 'image'">
                ` : `
                    <video controls class="w-full h-auto max-h-[500px] object-cover rounded-xl">
                        <source src="${media.path}" type="video/mp4">
                    </video>
                `).join('')}
            </div>
        ` : videoIdContent && !post.media_path && !post.shared_link ? `
            <div class="mb-3 flex justify-center">
                <iframe class="w-full max-w-2xl h-64 rounded-xl" src="https://www.youtube.com/embed/${videoIdContent}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        ` : '';

        const postHtml = `
            <article class="p-4 border-b border-dark-border" id="post-${post.id}">
                <div class="flex gap-4">
                    <a href="/profile/${post.user.username}" class="flex-shrink-0">
                        <img src="${post.user.profile_picture || '/images/default-profile.png'}" alt="${post.user.name}" class="w-12 h-12 rounded-full object-cover hover:opacity-90 transition-opacity">
                    </a>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <a href="/profile/${post.user.username}" class="font-bold hover:underline">${post.user.name}</a>
                            <span class="text-gray-500">@${post.user.username}</span>
                            <span class="text-gray-500">·</span>
                            <time class="text-gray-500 hover:underline">${post.created_at}</time>
                            ${isOwner ? `
                                <div class="relative ml-auto">
                                    <button @click="$refs.options${post.id}.classList.toggle('hidden')" class="p-2 rounded-full hover:bg-zinc-800 transition-colors">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <div x-ref="options${post.id}" class="hidden absolute right-0 mt-1 w-48 bg-zinc-900 border border-zinc-700 rounded-lg shadow-xl z-10">
                                        <button @click="deletePost(${post.id}); $refs.options${post.id}.classList.add('hidden')" class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-zinc-800 rounded-lg">
                                            <i class="fa-solid fa-trash-can w-5"></i>
                                            <span>Delete Post</span>
                                        </button>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                        <div class="block">
                            ${contentWithoutUrl ? `<p class="mb-3 text-[15px] leading-relaxed">${contentWithoutUrl.replace(/\n/g, '<br>')}</p>` : ''}
                            ${mediaHtml}
                            <a href="/post/${post.id}" class="block text-blue-500 hover:underline text-sm mt-2">View Post</a>
                        </div>
                        <div class="flex justify-start gap-8">
                            <a href="/post/${post.id}#comments" class="flex items-center gap-2 hover:text-blue-500 transition-colors group" aria-label="Comments">
                                <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                    <i class="fa-regular fa-comment"></i>
                                </div>
                                <span>${post.comments_count || 0}</span>
                            </a>
                            <form action="/posts/${post.id}/like" method="POST" class="like-form">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                <button class="like-btn flex items-center gap-2 hover:text-red-500 transition-colors group" aria-label="Like" data-post-id="${post.id}" data-liked="false">
                                    <div class="p-2 rounded-full group-hover:bg-red-500/10 transition-colors">
                                        <i class="far fa-heart"></i>
                                    </div>
                                    <span class="like-count">${post.likes_count || 0}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        `;
        container.insertAdjacentHTML('afterbegin', postHtml);
    };

    window.showToast = (message, postId) => {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center gap-2';
        toast.innerHTML = `${message}${postId ? `<a href="/post/${postId}" class="text-white underline hover:text-gray-200">View</a>` : ''}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    };
});