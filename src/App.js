import React, { useState, useEffect } from "react";
import { Routes, Route, useNavigate } from "react-router-dom";
import Movies from "./components/Movies";
import Contact from "./components/Contact";
import Navbar from "./components/Navbar";
import Home from "./components/Home";
import Addtocart from "./components/Addtocart";
import Cart from "./components/Cart";
import "./App.css";

function App() {
  const [cartItems, setCartItems] = useState(loadCartItemsFromStorage());
  const navigate = useNavigate();

  useEffect(() => {
    saveCartItemsToStorage(cartItems);
  }, [cartItems]);

  const handleAddToCart = (item) => {
    
    const itemExists = cartItems.some((cartItem) => cartItem.id === item.id);

    if (!itemExists) {
      setCartItems((prevItems) => [...prevItems, item]);
    } else {
      console.log("Item already added to the cart.");
    }
  };

  const handleRemoveFromCart = (itemId) => {
    setCartItems((prevItems) => prevItems.filter((item) => item.id !== itemId));
  };

  function loadCartItemsFromStorage() {
    const storedItems = localStorage.getItem("cartItems");
    return storedItems ? JSON.parse(storedItems) : [];
  }

  function saveCartItemsToStorage(items) {
    localStorage.setItem("cartItems", JSON.stringify(items));
  }

  return (
    <>
      <Navbar size={cartItems.length} />
      <Routes>
        <Route path="/Home" element={<Home />} />
        <Route path="/Contact" element={<Contact />} />
        <Route path="/Movies" element={<Movies />} />
        <Route
          path="/Addtocart/:id"
          element={<Addtocart addToCart={handleAddToCart} navigate={navigate} />}
        />
        <Route
          path="/Cart"
          element={<Cart cartItems={cartItems} removeItem={handleRemoveFromCart} />}
        />
      </Routes>
    </>
  );
}

export default App;
