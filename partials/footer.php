<footer class="footer">
    <p>© 2026 Kasir App</p>
</footer>

<script src="../assets/js/script.js"></script>

<script>

/* =========================
   DARK MODE
========================= */

const darkBtn =
document.getElementById(
    'darkModeBtn'
);

/* LOAD MODE */

if(localStorage.getItem('darkMode')
=== 'ON'){

    document.body.classList.add(
        'dark'
    );

}

/* TOGGLE */

darkBtn.addEventListener(
    'click',
    function(){

        document.body.classList.toggle(
            'dark'
        );

        /* SAVE */

        if(
            document.body.classList.contains(
                'dark'
            )
        ){

            localStorage.setItem(
                'darkMode',
                'ON'
            );

        }else{

            localStorage.setItem(
                'darkMode',
                'OFF'
            );

        }

    }
);

</script>

</body>
</html>