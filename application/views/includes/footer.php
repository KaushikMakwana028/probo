	<?php if (isset($page_type) && $page_type === 'dashboard'): ?>
		</div>
	</main>
</div>
	<?php endif; ?>
	<?php if (!isset($page_type) || $page_type !== 'auth'): ?>
	<footer class="site-footer">
		<p>Copyright <?php echo date('Y'); ?>. All rights reserved.</p>
	</footer>
	<?php endif; ?>
	<script>
		(function () {
			var body = document.body;
			var menuToggle = document.getElementById('menuToggle');
			var sidebarOverlay = document.getElementById('sidebarOverlay');
			var profileTrigger = document.getElementById('profileTrigger');
			var profileMenu = document.getElementById('profileMenu');
			var notificationTrigger = document.getElementById('notificationTrigger');
			var notificationMenu = document.getElementById('notificationMenu');

			function isMobile() {
				return window.innerWidth <= 920;
			}

			function closeSidebar() {
				body.classList.remove('sidebar-open');
			}

			function closeProfileMenu() {
				if (profileMenu) {
					profileMenu.classList.remove('open');
				}
			}

			function closeNotificationMenu() {
				if (notificationMenu) {
					notificationMenu.classList.remove('open');
				}
			}

			function toggleSidebar() {
				closeProfileMenu();
				closeNotificationMenu();

				if (isMobile()) {
					body.classList.toggle('sidebar-open');
					body.classList.remove('sidebar-collapsed');
				} else {
					body.classList.toggle('sidebar-collapsed');
					body.classList.remove('sidebar-open');
				}
			}

			if (menuToggle) {
				menuToggle.addEventListener('click', toggleSidebar);
			}

			if (sidebarOverlay) {
				sidebarOverlay.addEventListener('click', closeSidebar);
			}

			if (profileTrigger && profileMenu) {
				profileTrigger.addEventListener('click', function (event) {
					event.stopPropagation();
					closeNotificationMenu();
					profileMenu.classList.toggle('open');
				});
			}

			if (notificationTrigger && notificationMenu) {
				notificationTrigger.addEventListener('click', function(event) {
					event.stopPropagation();
					closeProfileMenu();
					notificationMenu.classList.toggle('open');
				});
			}

			document.addEventListener('click', function (event) {
				if (profileMenu && !profileMenu.contains(event.target)) {
					closeProfileMenu();
				}

				if (notificationMenu && !notificationMenu.contains(event.target)) {
					closeNotificationMenu();
				}
			});

			Array.prototype.slice.call(document.querySelectorAll('.sidebar-nav a')).forEach(function (link) {
				link.addEventListener('click', function () {
					if (isMobile()) {
						closeSidebar();
					}
				});
			});

			window.addEventListener('resize', function () {
				if (!isMobile()) {
					body.classList.remove('sidebar-open');
				}

				closeProfileMenu();
				closeNotificationMenu();
			});

			var profileInput = document.getElementById('profile_image_file');
			if (profileInput) {
				profileInput.addEventListener('change', function (event) {
					var file = event.target.files && event.target.files[0];
					if (!file) {
						return;
					}

					var reader = new FileReader();
					reader.onload = function (loadEvent) {
						var targets = (profileInput.getAttribute('data-preview-targets') || '').split(',');
						targets.forEach(function (targetId) {
							var trimmedId = targetId.trim();
							if (!trimmedId) {
								return;
							}

							var image = document.getElementById(trimmedId);
							if (image) {
								image.src = loadEvent.target.result;
							}
						});
					};

					reader.readAsDataURL(file);
				});
			}
 		})();
	</script>
	<script>
		(function () {
			window.userSwalAlert = function(message, icon, title) {
				if (typeof Swal === 'undefined') {
					return;
				}

				return Swal.fire({
					icon: icon || 'info',
					title: title || '',
					text: message,
					confirmButtonColor: '#2563eb'
				});
			};

			window.alert = function(message) {
				return window.userSwalAlert(String(message || ''), 'error');
			};

			window.userSwalConfirm = function(message, options) {
				if (typeof Swal === 'undefined') {
					return Promise.resolve(window.confirm(message));
				}

				var config = Object.assign({
					icon: 'warning',
					text: message,
					showCancelButton: true,
					confirmButtonText: 'Yes',
					cancelButtonText: 'Cancel',
					confirmButtonColor: '#2563eb',
					cancelButtonColor: '#94a3b8'
				}, options || {});

				return Swal.fire(config).then(function(result) {
					return !!result.isConfirmed;
				});
			};

			function collectFlashMessages() {
				var selectors = [
					'.message.error',
					'.message.success',
					'.question-flash',
					'.answer-flash',
					'.qf',
					'.wv-alert',
					'.dep-alert',
					'.wallet-alert',
					'.users-flash',
					'.user-edit-flash',
					'.user-detail-flash',
					'.question-edit-flash',
					'.question-list-flash',
					'.ref-alert',
					'.profile-flash',
					'.alert-message'
				];

				var nodes = document.querySelectorAll(selectors.join(','));
				nodes.forEach(function (node) {
					var text = (node.innerText || node.textContent || '').trim();
					if (!text) {
						node.style.display = 'none';
						return;
					}

					var isError = /error|danger/i.test(node.className);
					var isSuccess = /success/i.test(node.className);
					var icon = isError ? 'error' : (isSuccess ? 'success' : 'info');

					Swal.fire({
						icon: icon,
						text: text,
						confirmButtonColor: '#2563eb'
					});

					node.style.display = 'none';
				});
			}

			function bindSweetConfirms() {
				document.querySelectorAll('[onclick]').forEach(function (element) {
					var onclickValue = element.getAttribute('onclick') || '';
					if (onclickValue.indexOf('confirm(') === -1) {
						return;
					}

					var match = onclickValue.match(/confirm\((['"`])([\s\S]*?)\1\)/);
					var message = match ? match[2].replace(/\\n/g, '\n') : 'Are you sure?';
					element.removeAttribute('onclick');

					element.addEventListener('click', function (event) {
						event.preventDefault();
						var href = element.getAttribute('href');
						userSwalConfirm(message).then(function(confirmed) {
							if (confirmed && href) {
								window.location.href = href;
							}
						});
					});
				});
			}

			if (typeof Swal !== 'undefined') {
				collectFlashMessages();
				bindSweetConfirms();
			}
		})();
	</script>
</body>
</html>
