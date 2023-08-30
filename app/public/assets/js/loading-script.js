window.onbeforeunload = function () {
    //location.reload();
    let timerInterval
    Swal.fire({
        allowEscapeKey: false,
        allowOutsideClick: false,
        title: 'Please Wait.',
        timer: 300000,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
        willClose: () => {
            clearInterval(timerInterval)
        },
        background: '#fff',
        backdrop:'rgba(37, 9, 65, 0.9)'
    }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
            //console.log('I was closed by the timer')
            window.history.forward();
        }
    })

}

// function preventBack() {
//     /*let currentState = window.history.state;
//     console.log(currentState);*/
//     window.history.forward();
// };

// setTimeout("preventBack()", -500);
// window.onload = preventBack();
// window.onunload = function () {
//     null
// };