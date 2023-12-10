import React from 'react';

function Menu(){
    const menuItems = ['Despensa', "Frutas Y Verduras", 'Congelados', 'Bebidas', 'Cuidado Personal'];

    return (
        <div className="menu">
            <ul className="menu-list" style={{ display: 'flex', listStyle: 'none', padding: 0 }}>
                {menuItems.map((item, index) => (
                    <li key={index} style={{ margin: '0 10px' }}>
                        <a href={`/${item.toLowerCase()}`}>{item}</a>
                    </li>
                ))}
            </ul>
        </div>
    )
}

export default Menu;
