import React, { Component } from 'react';
import { Label, Col, Button, Row, Input, Alert } from 'reactstrap';
import { Digest } from '../../containers/Helpers/digest';
import {
  baseUrl,
  path_users,
  path_assessments
  // path_assessments
} from '../../components/config/config';
import axios from 'axios';
import moment from 'moment';

export default class SuratTugasPleno extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: {
        pleno_id: '',
        position: '',
        pleno_date: ''
      },
      hidden: true,
      message: '',
      payloadAssessor: [],
      payload: []
    };
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  // createUI() {
  //   return this.state.anggota.map((el, i) => (
  //     <div key={i}>
  //       <p />
  //       <Row>
  //         <Col md="3">
  //           <Label>Anggota {i + 1}</Label>
  //         </Col>
  //         <Col md="4">
  //           <Input
  //             type="text"
  //             value={el || ''}
  //             onChange={this.handleChange.bind(this, i)}
  //             placeholder="Nama Anggota"
  //           />
  //         </Col>
  //         <Button
  //           color="danger"
  //           value="remove"
  //           onClick={this.removeClick.bind(this, i)}
  //         >
  //           <i className="fa fa-remove" /> Remove
  //         </Button>
  //       </Row>{' '}
  //       <p />
  //     </div>
  //   ));
  // }

  Get(options, response) {
    axios(options).then(res => {
      this.setState({
        [response]: res.data.data
      });
    });
  }

  componentDidMount() {
    const { assessment_id } = this.props.params;
    const authAsesors = Digest(path_users, 'GET');
    const authentication = Digest(
      path_assessments + '/' + assessment_id,
      'GET'
    );
    const optionAssessors = {
      method: authAsesors.method,
      headers: {
        Authorization: authAsesors.digest,
        'X-Lsp-Date': authAsesors.date
      },
      url: baseUrl + path_users + '?role_code=ACS,ADM&limit=100',
      data: null
    };
    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        'X-Lsp-Date': authentication.date
      },
      url: baseUrl + path_assessments + '/' + assessment_id,
      data: null
    };

    this.Get(optionAssessors, 'payloadAssessor');
    this.Get(options, 'payload');
  }

  handleChange = event => {
    this.setState({ [event.target.name]: event.target.value });
  };

  handleSubmit = event => {
    event.preventDefault();
    const { assessment_id } = this.props.params;
    var pleno_date = moment(this.state.pleno_date).format(
      'YYYY-MM-DD HH:mm:ss'
    );
    var data = {};
    var date = {};

    const authentication = Digest(
      path_assessments + '/' + assessment_id + '/plenos',
      'POST'
    );
    data['pleno_id'] = this.state.pleno_id;
    data['position'] = this.state.position;
    date['pleno_date'] = pleno_date;
    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        'X-Lsp-Date': authentication.date,
        'Content-Type': 'multipart/form-data'
      },
      url: `${baseUrl}${path_assessments}/${assessment_id}/plenos`,
      data: data
    };
    axios(options)
      .then(res => {
        if (res.status === 201) {
          window.alert(`success Assign ${this.state.position}`);
        } else {
          return;
        }
      })
      .catch(error => {
        let responseJSON = error.response;
        this.setState({
          response: responseJSON.data.error.code
        });
        switch (this.state.response) {
          case 400:
            this.setState({
              hidden: false,
              message: multiLanguage.alertInput
            });
            break;
          case 409:
            this.setState({
              hidden: false,
              message: 'User Already in any position'
            });
            break;

          default:
            break;
        }
      });
  };
  render() {
    const { title, pleno_date } = this.state.payload;
    return (
      <div className="animated fadeIn">
        <form onSubmit={this.handleSubmit} name="test-form">
          <Row>
            <Col md="3">
              <Label htmlFor="assessment_id">Assessment</Label>
            </Col>
            <Col xs="5" md="4">
              <Input
                type="text"
                name="assessment_id"
                defaultValue={title}
                readOnly
              />
            </Col>
          </Row>
          <p />
          <Row>
            <Col md="3">
              <Label htmlFor="pleno_date">Pleno Date</Label>
            </Col>
            <Col xs="5" md="4">
              <Input
                type="date"
                name="pleno_date"
                defaultValue={moment(pleno_date).format('YYYY-MM-DD')}
                onChange={this.handleChange}
              />
            </Col>
          </Row>
          <p />
          <Row>
            <Col md="3">
              <Label>Name</Label>
            </Col>
            <Col xs="5" md="4">
              <Input
                type="select"
                id="pleno_id"
                name="pleno_id"
                onChange={this.handleChange}
              >
                <option value="">Select Staff</option>
                {this.state.payloadAssessor.map(
                  ({ user_id, first_name }, key) => {
                    return (
                      <option value={user_id} key={user_id}>
                        {' '}
                        {first_name}
                      </option>
                    );
                  }
                )}
              </Input>
            </Col>
          </Row>
          <p />
          <Row>
            <Col md="3">
              <Label>Position</Label>
            </Col>
            <Col xs="5" md="4">
              <Input type="select" name="position" onChange={this.handleChange}>
                <option value="">Select Position</option>
                <option value="KETUA">Ketua</option>
                <option value="ANGGOTA_1">Anggota 1</option>
                <option value="ANGGOTA_2">Anggota 2</option>
              </Input>
            </Col>
          </Row>
          {/* {this.createUI()} */}
          {/* <Button
            color="primary"
            value="add more"
            onClick={this.addClick.bind(this)}
          >
            <i className="fa fa-plus" /> Add Anggota
          </Button>{' '} */}
        </form>
        <p />
        <Button
          color="success"
          type="submit"
          value="Submit"
          onClick={this.handleSubmit}
        >
          <i className="fa fa-check" /> Submit
        </Button>
        <p />
        <Alert
          color="danger"
          hidden={this.state.hidden}
          className="text-center"
        >
          {this.state.message}
        </Alert>
      </div>
    );
  }
}
