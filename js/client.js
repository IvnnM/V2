function sendLoginRequest(form) {
  const formData = new FormData(form);

  $.ajax({
    type: "POST",
    url: "./php/client-login-process.php",
    data: formData,
    processData: false, // Prevent jQuery from processing the data
    contentType: false, // Prevent jQuery from setting the content type
    success: function (response) {
      var data = JSON.parse(response);

      if (data.status === "error") {
        Swal.fire({
          position: "center",
          icon: "error",
          title: "Invalid login",
          text: data.message,
          showConfirmButton: true
        });
        form.querySelector('[name="password"]').value = '';
      } else if (data.status === "success") { 
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 2500,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
          },
        });
        
        Toast.fire({
          icon: 'success',
          title: data.message,
          willClose: () => {
            if (data.role === "Client") {
              window.location.href = "client.php";
            } else {
              window.location.href = "index.html";
            }
          },
        });
        form.reset();
      } else if (data.status === "warning") {
        Swal.fire({
          icon: 'warning',
          title: 'Incorrect password',
          text: data.message,
          footer: '<a href="#" id="forgotPasswordLink">Forgot password?</a>',
        });
        
        form.querySelector('[name="password"]').value = '';
        
        // Add a click event listener to the "Forgot password?" link
        document.getElementById('forgotPasswordLink').addEventListener('click', function (e) {
          e.preventDefault(); // Prevent the link from navigating
          Swal.fire({
            title: 'Relaxation image',
            text: 'Relax and try to remember your password.',
            imageUrl: 'https://unsplash.it/400/200',
            imageWidth: 400,
            imageHeight: 200,
            imageAlt: 'Relax image',
            confirmButtonText: 'THANKS',
          });
        });
        // Add a click event listener to the "Forgot password?" link
      }
    },
    error: function () {
      alert("An error occurred while processing your request. Please try again later.");
    }
  });

  // Prevent the default form submission
  return false;
}