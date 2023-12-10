import React from "react";

function Head(){
    return (
        <div className="header" style={{ display: 'flex', alignItems: 'center', margin: "20px"}}>
            <a href="/inicio"> {/* Enlace a la página de inicio */}
                <img src="/logoPasadena.png" alt="Logo de la aplicacion" width="400px" style={{ display: 'inline-block' }} />
            </a>
            <span style={{ display: 'inline-block', margin: '40px', fontSize: "x-large" }}>
                Cra. 10 #14-75, Sogamoso centro, Boyacá
            </span>
            <div style={{ display: 'inline-block', margin: '10px' }}> {/* Utiliza un div para agrupar el botón y el texto */}
                <button style={{
                    display: 'block',
                    margin: '10px 0', // Añade margen para separar el botón del texto
                    backgroundImage: `url(${process.env.PUBLIC_URL}/usuario.png)`, // Establece la imagen como fondo
                    backgroundSize: 'cover',
                    width: '100px', // Ancho del botón
                    height: '100px', // Altura del botón
                    border: 'none', // Borde del botón
                    borderRadius: '20px',
                    cursor: 'pointer' // Cambia el cursor al pasar por encima del botón
                }}></button>
                <span style={{ display: 'block', textAlign: 'center' }}>Iniciar Sesion</span>
            </div>
            <div style={{ display: 'inline-block', margin: '10px' }}>

            </div>
            {/* Contenido del heat */}
        </div>
    )
}

export default Head;