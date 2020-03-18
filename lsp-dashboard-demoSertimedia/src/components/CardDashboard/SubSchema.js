import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import { Col, Row, Card, CardBody } from 'reactstrap';
import { path_subSchema, getData, getRole } from '../config/config';
import Axios from 'axios';
import { multiLanguage } from '../Language/getBahasa';

export default class SubSchema extends Component {
  constructor(props) {
    super(props);
    this.state = {
      jmlahSubSchema: ''
    };
  }

  Get(options, response) {
    Axios(options).then(res => {
      this.setState({
        [response]: res.data.count
      });
    });
  }

  componentDidMount() {
    const path = path_subSchema;
    const method = 'GET';
    this.Get(getData(path, method), 'jmlahSubSchema');
  }

  render() {
    return (
      <div>
        <Card className="text-white img-accessor">
          <CardBody>
            <Row>
              <Col>
                <h3>
                  <Row>
                    <Col md="auto">
                      <i className="colorIcon fa icon-grid fa-2x" />
                    </Col>
                    <Col md="auto" className="jmlahCarousel">
                      {this.state.jmlahSubSchema === '0'
                        ? 'empty'
                        : `${this.state.jmlahSubSchema}`}
                    </Col>
                    <Col md="5" className="textCarousel">
                      Sub Schema
                    </Col>
                  </Row>
                  <hr />
                  {getRole() !== 'ACS' ? (
                    <Link to={'/schema/sub-schema'} className="text">
                      <Col>{multiLanguage.viewMore}</Col>
                    </Link>
                  ) : (
                    ''
                  )}
                </h3>
              </Col>
            </Row>
          </CardBody>
        </Card>
      </div>
    );
  }
}
