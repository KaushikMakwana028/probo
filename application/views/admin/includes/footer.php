<?php if (isset($page_type) && $page_type === 'dashboard' && isset($admin)): ?>

	</div><!-- /.page-grid -->
	</main><!-- /.main-area -->
	</div><!-- /.app-layout -->

<?php endif; ?>

<script>
	(function() {
		'use strict';

		function initAdminUI() {

			var body = document.body;
			var toggle = document.getElementById('menuToggle');
			var overlay = document.getElementById('sidebarOverlay');
			var profMenu = document.getElementById('profileMenu');
			var profBtn = document.getElementById('profileTrigger');
			var profDrop = document.getElementById('profileDropdown');

			/* Sidebar */
			function closeSidebar() {
				body.classList.remove('sb-open');
			}

			function openSidebar() {
				body.classList.add('sb-open');
			}

			if (toggle) toggle.addEventListener('click', function() {
				body.classList.contains('sb-open') ? closeSidebar() : openSidebar();
			});

			if (overlay) overlay.addEventListener('click', closeSidebar);

			/* Profile dropdown */
			if (profBtn && profMenu) {

				function openDrop() {
					profMenu.classList.add('open');
					profBtn.setAttribute('aria-expanded', 'true');
				}

				function closeDrop() {
					profMenu.classList.remove('open');
					profBtn.setAttribute('aria-expanded', 'false');
				}

				profBtn.addEventListener('click', function(e) {
					e.stopPropagation();
					profMenu.classList.contains('open') ? closeDrop() : openDrop();
				});

				document.addEventListener('click', function(e) {
					if (!profMenu.contains(e.target)) closeDrop();
				});
			}

			/* Sidebar dropdown (Questions menu) */
			document.querySelectorAll('.sidebar-group-toggle').forEach(function(btn) {

				// REMOVE old listeners (important in CI reloads)
				btn.replaceWith(btn.cloneNode(true));
			});

			document.querySelectorAll('.sidebar-group-toggle').forEach(function(btn) {

				btn.addEventListener('click', function(e) {
					e.preventDefault();
					e.stopPropagation();

					const group = this.closest('.sidebar-group');

					if (!group) return;

					// Close others
					document.querySelectorAll('.sidebar-group').forEach(function(g) {
						if (g !== group) g.classList.remove('open');
					});

					// Toggle current
					group.classList.toggle('open');
				});

			});
		}

		/* RUN IMMEDIATELY if already loaded */
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', initAdminUI);
		} else {
			initAdminUI();
		}
	}());
</script>
<script>
	(function() {
		function collectFlashMessages() {
			var selectors = [
				'.message.error',
				'.message.success',
				'.question-list-flash',
				'.question-edit-flash',
				'.users-flash',
				'.user-edit-flash',
				'.user-detail-flash',
				'.withdraw-flash',
				'.ref-alert',
				'.alert-message'
			];

			var nodes = document.querySelectorAll(selectors.join(','));
			nodes.forEach(function(node) {
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
			document.querySelectorAll('[onclick]').forEach(function(element) {
				var onclickValue = element.getAttribute('onclick') || '';
				if (onclickValue.indexOf('confirm(') === -1) {
					return;
				}

				var match = onclickValue.match(/confirm\((['"`])([\s\S]*?)\1\)/);
				var message = match ? match[2].replace(/\\n/g, '\n') : 'Are you sure?';
				element.removeAttribute('onclick');

				element.addEventListener('click', function(event) {
					event.preventDefault();
					var href = element.getAttribute('href');
					Swal.fire({
						icon: 'warning',
						text: message,
						showCancelButton: true,
						confirmButtonText: 'Yes',
						cancelButtonText: 'Cancel',
						confirmButtonColor: '#2563eb',
						cancelButtonColor: '#94a3b8'
					}).then(function(result) {
						if (result.isConfirmed && href) {
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
	}());
</script>

</body>

</html>
