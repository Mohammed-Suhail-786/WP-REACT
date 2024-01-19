import React from "react";
import { Link } from "react-router-dom";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faShoppingCart } from '@fortawesome/free-solid-svg-icons';


const Navbar = ({ size }) => {
  

  return (
    <>
        <nav>
          <p className="navmovie">
            <strong>PRODUCTS SITE</strong>
          </p>
          <ul >
            <Link to="Home">
              <li>HOME</li>
            </Link>
            <Link to="/Movies">
              <li>PRODUCTS</li>
            </Link>
            <Link to="/Contact">
              <li>CONTACT</li>
            </Link>
            <Link to="/cart" className="cart-link">
          <div className="cart-icon">
          <FontAwesomeIcon icon={faShoppingCart} size="2x" />
          <span style={{ color: "white" }}>{size}</span>
        </div>
          </Link>
          </ul>
        </nav>
     
    </>
  );
};

export default Navbar;