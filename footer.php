

<footer class="footer">
        <div class="footer-content">
            <p class="footer-text">TiendaOnline 2025 &copy; Todos los derechos reservados.</p>
            <div class="social-icons">
                <a href="https://www.facebook.com" target="_blank" class="social-icon">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="https://www.instagram.com" target="_blank" class="social-icon">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://wa.me/1234567890" target="_blank" class="social-icon">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <a href="https://www.google.com" target="_blank" class="social-icon">
                    <i class="fab fa-google"></i>
                </a>
            </div>
        </div>
    </footer>
  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<style>
    body {
    margin: 0;
   box-sizing: border-box;
}

.footer {
    background-color: #000;
    color: #fff;
    padding: 20px 0;
    text-align: center;
}

.footer .footer-content {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.footer .footer-text {
    margin: 0;
    font-size: 16px;
    
}

.social-icons {
    margin-top: 10px;display: flex;flex-direction: row;
}

.social-icon {
    color: #fff;
    font-size: 24px;
    margin: 0 10px;
    text-decoration: none;
}

.social-icon:hover {
    color: #3498db; /* Color de hover, puedes cambiarlo */
}
tr  {
    border-bottom: 2px solid #ccc;  /* Borde inferior */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra sutil */
    text-align: center;
  }
  th{
    border-top: 2px solid #ccc;  /* Borde inferior */
    box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1); /* Sombra sutil */
    padding: 20px 5px;
  }
 .img {
    transition: transform 1s ease; /* Transición suave */
    border-radius: 2px;width: 60px;
    height: 50px;
    object-fit: cover;
    object-position: center; border-radius: 5px;
  }
  
  .img:hover {
    transform: scale(1.4);
    z-index: 1; 
 
 
  }
  .img2 {
    transition: transform 1s ease; /* Transición suave */
    border-radius: 2px;width: 100%;
    height: 120px;
    
    object-position: center; border-radius: 5px;
  }
  
  .img2:hover {
    transform: scale(1.4);
    z-index: 1; 
 
 
  }
  .descuento {
    font-size: 2em; 
    font-weight: bold;
    color: red; 
}
.elemento {
  position: relative;
  display: inline-block;
  font-size: 16px;
  color: #333;
  text-decoration: none;
}

/* Pseudo-elemento before */
.elemento::before {
  content: ''; /* Necesario para crear el pseudo-elemento */
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0; /* Inicialmente no se ve */
  height: 2px; /* El grosor del borde */
  background-color: black; /* El color del borde */
  transition: width 0.5s ease; /* Animamos el cambio de ancho */
}

/* Estilo cuando está en hover */
.elemento:hover::before {
  width: 100%; /* El borde se expande completamente */
}
</style>

  </body>
</html>