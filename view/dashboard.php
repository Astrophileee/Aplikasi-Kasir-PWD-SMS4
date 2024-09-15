<?php include_once "admin/layouts/session.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php require_once "admin/layouts/header.php"; ?>
</head>

<body>
<?php require_once "admin/layouts/sidebar.php"; ?>
        <div class="main p-3">
            <div class="text-center">
                <h1>
                    DASHBOARD
                </h1>
            </div>
        </div>
    </div>
<?php require_once "admin/layouts/footer.php"; ?>
<script>
    window.addEventListener('load', function () {
    let urlParams = new URLSearchParams(window.location.search)
    let success = urlParams.get('success')
    let message = urlParams.get('message')

    if (success && message) {
        if (success === 'true') {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 1500,
            })
        } else {
            Swal.fire({
                title: 'Error!',
                text: message,
                icon: 'error',
            })
        }
        window.history.replaceState(
            {},
            document.title,
            window.location.pathname
        )
    }
});
</script>

</body>

</html>