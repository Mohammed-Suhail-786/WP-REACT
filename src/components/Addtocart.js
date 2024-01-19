import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import "./Addtocart.css";

const Addtocart = ({ addToCart, navigate }) => {
  const { id } = useParams();
  const [details, setDetails] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await fetch(`http://localhost/Api_data/customers/read.php?id=${id}`);
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        const data = await response.json();
        setDetails(data);
      } catch (err) {
        console.error("Error fetching data:", err);
        setError(err);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [id]);

  const handleAddToCart = () => {
    
    addToCart(details.data);

    navigate();
  };

  return (
    <div className="add-to-cart-container">
      <h2>Details for Item with ID: {id}</h2>
      {loading && <p>Loading...</p>}
      {error && <p>Error: {error.message}</p>}
      {details && (
        <>
          <p>Name: {details?.data?.emp_name}</p>
          <p>Email: {details?.data?.emp_email}</p>
          <p>Department: {details?.data?.emp_dept}</p>
          <button className="add-to-cart-button" onClick={handleAddToCart}>
            Add to Cart
          </button>
        </>
      )}
    </div>
  );
};

export default Addtocart;
