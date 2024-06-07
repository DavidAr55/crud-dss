<?php if (isset($_SESSION['executeSuccess'])) : ?>
    <script>
        let mensaje = '<?php echo $_SESSION['executeSuccess']; ?>'
        Swal.fire({
            title: "¡Listo!",
            text: mensaje,
            icon: "success",
            confirmButtonColor: '#111C4E',
            customClass: {
                title: 'my-swal-title-class',
                content: 'my-swal-content-class',
            },
        });
    </script>
<?php unset($_SESSION['executeSuccess']);
endif; ?>

<?php if (isset($_SESSION['executeError'])) : ?>
    <script>
        let mensaje = '<?php echo $_SESSION['executeError']; ?>'
        Swal.fire({
            title: "Error!",
            text: mensaje,
            icon: "error",
            confirmButtonColor: '#111C4E',
            customClass: {
                title: 'my-swal-title-class',
                content: 'my-swal-content-class',
            },
        });
    </script>
<?php unset($_SESSION['executeError']);
endif; ?>

<?php if (isset($_SESSION['executeWarning'])) : ?>
    <script>
        let mensaje = '<?php echo $_SESSION['executeWarning']; ?>'
        Swal.fire({
            title: "Advertencia!",
            text: mensaje,
            icon: "warning",
            confirmButtonColor: '#111C4E',
            customClass: {
                title: 'my-swal-title-class',
                content: 'my-swal-content-class',
            },
        });
    </script>
<?php unset($_SESSION['executeWarning']);
endif; ?>