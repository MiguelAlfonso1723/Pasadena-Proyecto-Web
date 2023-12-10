import './App.css';
import Home from "./Pages/Home";
import Menu from "./Pages/Menu";
import Footer from "./Pages/Footer";
import Head from "./Pages/Head";

function App() {
  return (
    <div className="App">
      <header>
          <Head />
          <Menu />
        {/* Contenido del encabezado común para todas las páginas */}
      </header>
      <main>
        <Home /> {/* Usa el componente de la página de inicio */}
        {/* Aquí podrías agregar más componentes de página */}
      </main>
      <footer>
        <Footer/>
        {/* Contenido del pie de página común para todas las páginas */}
      </footer>
    </div>
  );
}

export default App;
