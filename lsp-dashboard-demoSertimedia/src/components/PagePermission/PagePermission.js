import React, { Component } from 'react';
import {Col, Row } from 'reactstrap';
import imgPermission from '../../assets/img/brand/img_permission.png';

class Page404 extends Component {
  render() {
    return (
      <div>
        <Row className="justify-content-center">
          <Col md="6">
            <img className="imgPermission" src={imgPermission} alt="" style={{width:'90%'}} />
          </Col>
          </Row>
      </div>
    );
  }
}

export default Page404;
