            </div> <!-- Cierra content-wrapper -->
            </main>
            </div> <!-- Cierra dashboard-container -->

            <footer class="footer">
                <div class="footer-content">
                    <div class="footer-section">
                        <h3>Biblioteca CRUBA</h3>
                        <p>Sistema de Información Gerencial para la gestión de recursos bibliográficos.</p>
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>

                    <div class="footer-section">
                        <h3>Enlaces Rápidos</h3>
                        <ul class="footer-links">
                            <li><a href="dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                            <li><a href="libros/index.php"><i class="fas fa-book"></i> Libros</a></li>
                            <li><a href="#"><i class="fas fa-users"></i> Estudiantes</a></li>
                            <li><a href="#"><i class="fas fa-exchange-alt"></i> Préstamos</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h3>Contacto</h3>
                        <ul class="contact-info">
                            <li><i class="fas fa-map-marker-alt"></i> Dirección: Av. Principal #123</li>
                            <li><i class="fas fa-envelope"></i> Email: info@cruba.edu</li>
                        </ul>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>&copy; <?php echo date('Y'); ?> Biblioteca CRUBA. Todos los derechos reservados.</p>
                </div>
            </footer>

            <script src="assets/js/script.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            <style>
                .footer {
                    background: linear-gradient(135deg, #2e59d9, #4e73df);
                    color: white;
                    padding: 40px 0 20px;
                    margin-top: auto;
                }

                .footer-content {
                    max-width: 1200px;
                    margin: 0 auto;
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 30px;
                    padding: 0 20px;
                }

                .footer-section h3 {
                    font-size: 1.2rem;
                    margin-bottom: 20px;
                    position: relative;
                    padding-bottom: 10px;
                }

                .footer-section h3::after {
                    content: '';
                    position: absolute;
                    left: 0;
                    bottom: 0;
                    width: 50px;
                    height: 2px;
                    background-color: rgba(255, 255, 255, 0.3);
                }

                .footer-section p {
                    color: rgba(255, 255, 255, 0.8);
                    line-height: 1.6;
                    margin-bottom: 15px;
                }

                .social-links {
                    display: flex;
                    gap: 15px;
                    margin-top: 20px;
                }

                .social-link {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 40px;
                    height: 40px;
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 50%;
                    color: white;
                    transition: all 0.3s ease;
                }

                .social-link:hover {
                    background: white;
                    color: #4e73df;
                    transform: translateY(-3px);
                }


                .footer-links {
                    list-style: none;
                    padding: 0;
                }

                .footer-links li {
                    margin-bottom: 10px;
                }

                .footer-links a {
                    color: rgba(255, 255, 255, 0.8);
                    text-decoration: none;
                    transition: all 0.3s ease;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                .footer-links a:hover {
                    color: white;
                    transform: translateX(5px);
                }

                .contact-info {
                    list-style: none;
                    padding: 0;
                }

                .contact-info li {
                    margin-bottom: 15px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    color: rgba(255, 255, 255, 0.8);
                }

                .contact-info i {
                    width: 20px;
                    text-align: center;
                }

                .footer-bottom {
                    text-align: center;
                    padding-top: 20px;
                    margin-top: 30px;
                    border-top: 1px solid rgba(255, 255, 255, 0.1);
                }

                .footer-bottom p {
                    color: rgba(255, 255, 255, 0.6);
                    font-size: 0.9rem;
                }

                @media (max-width: 768px) {
                    .footer-content {
                        grid-template-columns: 1fr;
                        text-align: center;
                    }

                    .footer-section h3::after {
                        left: 50%;
                        transform: translateX(-50%);
                    }

                    .social-links {
                        justify-content: center;
                    }

                    .footer-links a {
                        justify-content: center;
                    }

                    .contact-info li {
                        justify-content: center;
                    }
                }
            </style>
            </body>

            </html>