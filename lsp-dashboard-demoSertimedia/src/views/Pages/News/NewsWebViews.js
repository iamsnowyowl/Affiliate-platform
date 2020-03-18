import React, { Component } from "react";
import { Col, Container, Row } from "reactstrap";
import Axios from "axios";
import {
  path_GET_article,
  baseUrl,
  formatDate
} from "../../../components/config/config";

import "../../../css/News.css";

export default class NewsWebViews extends Component {
  constructor(props) {
    super(props);
    this.state = {
      author: "",
      categories: "",
      content: "",
      creator: "",
      created_date: "",
      media: null,
      tags: "",
      title: "",
      payload: []
    };
  }

  componentDidMount = () => {
    const article_id = this.props.match.params.article_id;
    Axios.get(baseUrl + path_GET_article + "/" + article_id).then(res => {
      this.setState({
        payload: res.data.data
      });
    });
  };

  render() {
    var {
      author,
      categories,
      content,
      creator,
      created_date,
      media,
      tags,
      title
    } = this.state.payload;
    console.log(baseUrl + media);
    const value = created_date;
    const creator_date = formatDate(value);
    return (
      <div style={{ backgroundColor: "white" }}>
        <Container>
          <Row className="justify-content-center">
            <Col md="6" style={{ paddingBottom: "15px", paddingTop: "15px" }}>
              <img
                src={baseUrl + media}
                alt="Gambar"
                style={{ width: "100%", height: "100%" }}
              />
            </Col>
          </Row>
          <Row>
            <Col>
              <div className="title">
                <h2>{title}</h2>
              </div>
            </Col>
          </Row>
          <Row>
            <Col md="6">
              <div className="creator">
                {creator_date} | {creator}-{categories}
              </div>
            </Col>
          </Row>
          <Row>
            <Col>
              <div dangerouslySetInnerHTML={{ __html: content }} />
            </Col>
          </Row>
          <Row>
            <Col>
              <p className="label">
                Author <span className="author">: {author}</span>
              </p>
            </Col>
          </Row>
          <Row>
            <Col md="2">
              <div className="label">Tags :</div>
            </Col>
            <p className="tags">{tags}</p>
          </Row>
        </Container>
      </div>
    );
  }
}
