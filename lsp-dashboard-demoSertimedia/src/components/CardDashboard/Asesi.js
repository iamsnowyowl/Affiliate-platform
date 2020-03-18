import React, { Component } from 'react';
import { Row, Col } from 'reactstrap';
import { Link } from 'react-router-dom';
import Axios from 'axios';
import { Skeleton } from 'antd';

import { path_applicantGeneral, getData } from '../config/config';
import { multiLanguage } from '../Language/getBahasa';

import '../../css/Dashboard.css';
import 'antd/dist/antd.css';

export default class Assessors extends Component {
  constructor(props) {
    super(props);
    this.state = {
      payload: [],
      loading: true
    };
  }

  componentDidMount() {
    const path = path_applicantGeneral;
    Axios(getData(path, 'GET')).then(response => {
      this.setState({
        payload: response.data,
        loading: false
      });
    });
  }

  render() {
    const { payload, loading } = this.state;
    return (
      <div>
        <Skeleton loading={loading} active avatar>
          <Row>
            <Col>
              <h3>
                <Row>
                  <Col md="auto">
                    <i className="colorIcon fa fa-user-circle-o fa-2x" />
                  </Col>
                  <Col md="auto" className="jmlahCarousel">
                    {payload.count === '0' ? '0' : `${payload.count}`}
                  </Col>
                  <Col md="auto" className="textCarousel">
                    {multiLanguage.asesi}
                  </Col>
                </Row>
                <hr />
                <Link to={'/users'} className="text">
                  <Col>{multiLanguage.viewMore}</Col>
                </Link>
              </h3>
            </Col>
          </Row>
        </Skeleton>
      </div>
    );
  }
}
