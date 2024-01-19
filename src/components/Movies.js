import React, { useState, useEffect } from "react";
import { Card, Row, Col, Button } from "antd";
import { Container } from "react-bootstrap";
import { Link } from "react-router-dom";

const Movies = () => {
  const [post, setPost] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch("http://localhost/Api_data/customers/read.php")
      .then((res) => {
        if (!res.ok) {
          throw new Error("Network response was not ok");
        }
        return res.json();
      })
      .then((response) => {
        console.log("Data received:", response);
        setPost(response.data); 
      })
      .catch((err) => {
        console.error("Error fetching data:", err);
        setError(err);
      })
      .finally(() => setLoading(false));
  }, []);

  return (
    <Container>
      <Row>
        {loading ? (
          <Col md={12}>
            <p>Loading...</p>
          </Col>
        ) : error ? (
          <Col md={12}>
            <p>Error: {error.message}</p>
          </Col>
        ) : (
          post.map((movie) => (
            <Col md={4} lg={6} sm={12} key={movie.id} className="card">
              <Link to={`/Addtocart/${movie.id}`}>
                <Card className="card1">
                  <img
                    src={movie.image}
                    alt={movie.name}
                    style={{
                      height: "300px",
                      width: "300px",
                      alignItems: "center",
                    }}
                  />
                  <p>
                    <strong>Release Date:</strong> {movie.emp_id}
                  </p>
                  <p>
                    <strong>Director:</strong> {movie.emp_name}
                  </p>
                  <p>
                    <strong>Budget:</strong> {movie.emp_email}
                  </p>
                  <p>
                    <strong>Ticket Price:</strong> {movie.emp_dept}
                  </p>
                  <Button className="button">VIEW MORE</Button>
                </Card>
              </Link>
            </Col>
          ))
        )}
      </Row>
    </Container>
  );
};

export default Movies;
