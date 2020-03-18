import React, { Component } from 'react';
import { Row, Col } from 'reactstrap';
import { Link } from 'react-router-dom';
import Axios from 'axios';
import { Skeleton } from 'antd';

import { path_users, getData } from '../config/config';
import { multiLanguage } from '../Language/getBahasa';

import 'antd/dist/antd.css';
import '../../css/Dashboard.css';

export default class Users extends Component {
  constructor(props) {
    super(props);
    this.state = {
      payload: [],
      loading: true
    };
  }

  componentDidMount() {
    const path = path_users;
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
      <div className="animated fadeIn">
        <Skeleton loading={loading} active avatar>
          <Row>
            <Col>
              <h3>
                <Row>
                  <Col md="auto">
                    <i className="colorIcon fa fa-users fa-2x" />
                  </Col>
                  <Col md="auto" className="jmlahCarousel">
                    {payload.count === '0' ? '0' : `${payload.count}`}
                  </Col>
                  <Col md="auto" className="textCarousel">
                    {multiLanguage.user}
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
