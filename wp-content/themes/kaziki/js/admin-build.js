/**
 * Admin Build System JavaScript
 * 
 * @package kaziki
 */

(function ($) {
    'use strict';

    $(document).ready(function () {

        // Generate Build
        $('#generate-build').on('click', function (e) {
            e.preventDefault();

            var $btn = $(this);
            var buildId = $btn.data('build-id');

            if (!buildId) {
                alert('Invalid build ID');
                return;
            }

            // Check if kazikiBuild is defined
            if (typeof kazikiBuild === 'undefined') {
                alert('Error: kazikiBuild is not defined. Please refresh the page.');
                console.error('kazikiBuild object is not defined');
                return;
            }

            console.log('Starting build generation for ID:', buildId);
            console.log('AJAX URL:', kazikiBuild.ajaxurl);
            console.log('Nonce:', kazikiBuild.nonce);

            // Disable button
            $btn.prop('disabled', true).text('Generating...');

            // Show progress
            $('#build-progress').show();
            $('#build-status-text').text('Generating build...');
            updateProgress(30);

            // AJAX request
            $.ajax({
                url: kazikiBuild.ajaxurl,
                type: 'POST',
                data: {
                    action: 'kaziki_generate_build',
                    build_id: buildId,
                    nonce: kazikiBuild.nonce
                },
                success: function (response) {
                    console.log('AJAX Response:', response);

                    if (response.success) {
                        $('#build-status-text').text('Build generated successfully!');
                        updateProgress(100);

                        alert('Build generated successfully!\\nPath: ' + (response.data.path || 'N/A'));

                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        $('#build-status-text').text('Error: ' + (response.data ? response.data.message : 'Unknown error'));
                        updateProgress(0);
                        $btn.prop('disabled', false).text('Generate Build');

                        alert('Error: ' + (response.data ? response.data.message : 'Unknown error'));
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', xhr, status, error);
                    console.error('Response Text:', xhr.responseText);

                    $('#build-status-text').text('Error: ' + error);
                    updateProgress(0);
                    $btn.prop('disabled', false).text('Generate Build');

                    alert('AJAX Error: ' + error + '\\nCheck console for details.');
                }
            });
        });

        // Rebuild
        $('#rebuild-build').on('click', function (e) {
            e.preventDefault();

            if (!confirm('Are you sure you want to rebuild? This will replace the current build.')) {
                return;
            }

            var $btn = $(this);
            var buildId = $btn.data('build-id');

            $btn.prop('disabled', true).text('Rebuilding...');

            $('#build-progress').show();
            $('#build-status-text').text('Rebuilding...');
            updateProgress(30);

            $.ajax({
                url: kazikiBuild.ajaxurl,
                type: 'POST',
                data: {
                    action: 'kaziki_rebuild',
                    build_id: buildId,
                    nonce: kazikiBuild.nonce
                },
                success: function (response) {
                    if (response.success) {
                        $('#build-status-text').text('Build rebuilt successfully!');
                        updateProgress(100);

                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        $('#build-status-text').text('Error: ' + response.data.message);
                        updateProgress(0);
                        $btn.prop('disabled', false).text('Rebuild');

                        alert('Error: ' + response.data.message);
                    }
                },
                error: function (xhr, status, error) {
                    $('#build-status-text').text('Error: ' + error);
                    updateProgress(0);
                    $btn.prop('disabled', false).text('Rebuild');

                    alert('Error: ' + error);
                }
            });
        });

        // Deploy to Cloudflare
        $('#deploy-cloudflare').on('click', function (e) {
            e.preventDefault();

            var $btn = $(this);
            var buildId = $btn.data('build-id');

            if (!confirm('Deploy this build to Cloudflare Pages?')) {
                return;
            }

            $btn.prop('disabled', true).text('Deploying...');

            $('#build-progress').show();
            $('#build-status-text').text('Deploying to Cloudflare Pages...');
            updateProgress(20);

            $.ajax({
                url: kazikiBuild.ajaxurl,
                type: 'POST',
                data: {
                    action: 'kaziki_deploy_cloudflare',
                    build_id: buildId,
                    nonce: kazikiBuild.nonce
                },
                success: function (response) {
                    if (response.success) {
                        $('#build-status-text').text('Deployed successfully!');
                        updateProgress(100);

                        alert('Deployed successfully!\\nURL: ' + response.data.url);

                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        $('#build-status-text').text('Error: ' + response.data.message);
                        updateProgress(0);
                        $btn.prop('disabled', false).text('Deploy to Cloudflare');

                        alert('Error: ' + response.data.message);
                    }
                },
                error: function (xhr, status, error) {
                    $('#build-status-text').text('Error: ' + error);
                    updateProgress(0);
                    $btn.prop('disabled', false).text('Deploy to Cloudflare');

                    alert('Error: ' + error);
                }
            });
        });

        // Download Build
        $('#download-build').on('click', function (e) {
            e.preventDefault();

            var buildId = $(this).data('build-id');

            // Create download URL
            var downloadUrl = kazikiBuild.ajaxurl + '?action=kaziki_download_build&build_id=' + buildId + '&nonce=' + kazikiBuild.nonce;

            // Trigger download
            window.location.href = downloadUrl;
        });

        // Update progress bar
        function updateProgress(percent) {
            $('#build-progress-bar').css('width', percent + '%');
        }

        // Show snapshot status message
        function showSnapshotStatus(message, isSuccess) {
            var $status = $('#snapshot-status');
            $status.removeClass('success error');
            $status.addClass(isSuccess ? 'success' : 'error');
            $status.text(message);
            $status.fadeIn();

            setTimeout(function () {
                $status.fadeOut();
            }, 5000);
        }

        // Save Snapshot
        $('#save-snapshot').on('click', function (e) {
            e.preventDefault();

            var $btn = $(this);
            var buildId = $btn.data('build-id');
            var name = $('#snapshot-name').val().trim();
            var description = $('#snapshot-description').val().trim();

            if (!name) {
                alert('Please enter a snapshot name');
                $('#snapshot-name').focus();
                return;
            }

            $btn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: kazikiBuild.ajaxurl,
                type: 'POST',
                data: {
                    action: 'kaziki_save_snapshot',
                    build_id: buildId,
                    name: name,
                    description: description,
                    nonce: kazikiBuild.nonce
                },
                success: function (response) {
                    if (response.success) {
                        showSnapshotStatus('Snapshot saved successfully!', true);
                        $('#snapshot-name').val('');
                        $('#snapshot-description').val('');

                        // Reload page to show new snapshot
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        showSnapshotStatus('Error: ' + response.data.message, false);
                        $btn.prop('disabled', false).html('<span class="dashicons dashicons-download" style="margin-top: 3px;"></span> Save Snapshot');
                    }
                },
                error: function (xhr, status, error) {
                    showSnapshotStatus('Error: ' + error, false);
                    $btn.prop('disabled', false).html('<span class="dashicons dashicons-download" style="margin-top: 3px;"></span> Save Snapshot');
                }
            });
        });

        // Restore Snapshot
        $('.restore-snapshot').on('click', function (e) {
            e.preventDefault();

            var $btn = $(this);
            var buildId = $btn.data('build-id');
            var snapshotId = $btn.data('snapshot-id');
            var snapshotName = $btn.data('snapshot-name');

            if (!confirm('Are you sure you want to restore "' + snapshotName + '"?\n\nThis will replace all current settings with the saved configuration.')) {
                return;
            }

            var originalHtml = $btn.html();
            $btn.prop('disabled', true).text('Restoring...');

            $.ajax({
                url: kazikiBuild.ajaxurl,
                type: 'POST',
                data: {
                    action: 'kaziki_restore_snapshot',
                    build_id: buildId,
                    snapshot_id: snapshotId,
                    nonce: kazikiBuild.nonce
                },
                success: function (response) {
                    if (response.success) {
                        showSnapshotStatus('Configuration restored successfully! Reloading...', true);

                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else {
                        showSnapshotStatus('Error: ' + response.data.message, false);
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                },
                error: function (xhr, status, error) {
                    showSnapshotStatus('Error: ' + error, false);
                    $btn.prop('disabled', false).html(originalHtml);
                }
            });
        });

        // Delete Snapshot
        $('.delete-snapshot').on('click', function (e) {
            e.preventDefault();

            var $btn = $(this);
            var buildId = $btn.data('build-id');
            var snapshotId = $btn.data('snapshot-id');
            var snapshotName = $btn.data('snapshot-name');

            if (!confirm('Are you sure you want to delete "' + snapshotName + '"?\n\nThis action cannot be undone.')) {
                return;
            }

            var originalHtml = $btn.html();
            $btn.prop('disabled', true).text('Deleting...');

            $.ajax({
                url: kazikiBuild.ajaxurl,
                type: 'POST',
                data: {
                    action: 'kaziki_delete_snapshot',
                    build_id: buildId,
                    snapshot_id: snapshotId,
                    nonce: kazikiBuild.nonce
                },
                success: function (response) {
                    if (response.success) {
                        showSnapshotStatus('Snapshot deleted successfully!', true);

                        // Remove the row from table
                        $btn.closest('tr').fadeOut(function () {
                            $(this).remove();

                            // Update count
                            var count = $('.config-snapshots tbody tr').length;
                            $('.config-snapshots h4').first().text('Saved Snapshots (' + count + ')');

                            // Show empty message if no snapshots
                            if (count === 0) {
                                location.reload();
                            }
                        });
                    } else {
                        showSnapshotStatus('Error: ' + response.data.message, false);
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                },
                error: function (xhr, status, error) {
                    showSnapshotStatus('Error: ' + error, false);
                    $btn.prop('disabled', false).html(originalHtml);
                }
            });
        });

        // Import Snapshot
        $('#import-snapshot').on('click', function (e) {
            e.preventDefault();

            var $btn = $(this);
            var buildId = $btn.data('build-id');
            var fileInput = $('#snapshot-file')[0];

            if (!fileInput.files || !fileInput.files[0]) {
                alert('Please select a JSON file to import');
                return;
            }

            var file = fileInput.files[0];

            if (!file.name.endsWith('.json')) {
                alert('Please select a valid JSON file');
                return;
            }

            var formData = new FormData();
            formData.append('action', 'kaziki_import_snapshot');
            formData.append('build_id', buildId);
            formData.append('snapshot_file', file);
            formData.append('nonce', kazikiBuild.nonce);

            var originalHtml = $btn.html();
            $btn.prop('disabled', true).text('Importing...');

            $.ajax({
                url: kazikiBuild.ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        showSnapshotStatus('Snapshot imported successfully! Reloading...', true);

                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else {
                        showSnapshotStatus('Error: ' + response.data.message, false);
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                },
                error: function (xhr, status, error) {
                    showSnapshotStatus('Error: ' + error, false);
                    $btn.prop('disabled', false).html(originalHtml);
                }
            });
        });

    });

})(jQuery);
