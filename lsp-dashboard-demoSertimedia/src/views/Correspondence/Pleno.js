import React, { Component } from 'react';
import {
  Card,
  CardHeader,
  CardBody,
  CardFooter,
  Label,
  Col,
  Alert,
  Button,
  Row,
  Input
} from 'reactstrap';
import { Digest } from '../../containers/Helpers/digest';
import {
  path_schedule_activity,
  baseUrl
} from '../../components/config/config';
import axios from 'axios';

export default class Pleno extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: {
        ketua: '',
        schedule_assessment_id: ''
      },
      anggota: [],
      hidden: false,
      subForm: true,
      payload: []
    };
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  createUI() {
    return this.state.anggota.map((el, i) => (
      <div key={i}>
        <p />
        <Row>
          <Col md="3">
            <Label>Anggota {i + 1}</Label>
          </Col>
          <Col md="4">
            <Input
              type="text"
              value={el || ''}
              onChange={this.handleChange.bind(this, i)}
              placeholder="Nama Anggota"
            />
          </Col>
          <Button
            color="danger"
            value="remove"
            onClick={this.removeClick.bind(this, i)}
          >
            <i className="fa fa-remove" /> Remove
          </Button>
        </Row>{' '}
        <p />
      </div>
    ));
  }

  Get(options, response) {
    axios(options).then(res => {
      this.setState({
        [response]: res.data.data
      });
    });
  }

  componentDidMount() {
    const auth = Digest(path_schedule_activity, 'GET');
    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        'X-Lsp-Date': auth.date,
        'Content-Type': 'application/json'
      },
      url: baseUrl + path_schedule_activity,
      data: null
    };
    this.Get(options, 'payload');
  }

  handleChangeKetua = event => {
    this.setState({ [event.target.name]: event.target.value });
  };

  handleChange(i, event) {
    let anggota = [...this.state.anggota];
    anggota[i] = event.target.value;
    this.setState({ anggota });
  }

  handleRole = event => {
    this.setState({ [event.target.name]: event.target.value });
    if (
      (event.target.name =
        'schedule_assessment_id' && event.target.value !== '')
    ) {
      this.setState({ subForm: false });
    } else {
      this.setState({ subForm: true });
    }
  };

  addClick() {
    this.setState(prevState => ({ anggota: [...prevState.anggota, ''] }));
  }

  removeClick(i) {
    let anggota = [...this.state.anggota];
    anggota.splice(i, 1);
    this.setState({ anggota });
  }

  handleSubmit(event) {
    event.preventDefault();
  }
  render() {
    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader>Formulir Pleno</CardHeader>
          <CardBody>
            <form onSubmit={this.handleSubmit}>
              <Row>
                <Col md="3">
                  <Label htmlFor="schedule_assessment_id">Assessment</Label>
                </Col>
                <Col xs="5" md="4">
                  <Input
                    type="select"
                    name="schedule_assessment_id"
                    id="schedule_assessment_id"
                    onChange={this.handleRole}
                    required
                  >
                    <option value="">Select Assessment</option>
                    {this.state.payload.map(
                      (
                        { schedule_assessment_id, competence_field_lable },
                        key
                      ) => {
                        return (
                          <option
                            value={schedule_assessment_id}
                            key={schedule_assessment_id}
                          >
                            {competence_field_lable}
                          </option>
                        );
                      }
                    )}
                  </Input>
                </Col>
              </Row>
              <p />
              <Alert color="light" hidden={this.state.subForm}>
                <Row>
                  <Col md="3">
                    <Label>Ketua Panitia</Label>
                  </Col>
                  <Col xs="5" md="4">
                    <Input
                      type="text"
                      id="ketua"
                      name="ketua"
                      placeholder="Ketua Pelaksana"
                      onChange={this.handleChangeKetua}
                    />
                  </Col>
                </Row>
                {this.createUI()}
                <Button
                  color="primary"
                  value="add more"
                  onClick={this.addClick.bind(this)}
                >
                  <i className="fa fa-plus" /> Add Anggota
                </Button>{' '}
              </Alert>
            </form>
          </CardBody>
          <CardFooter>
            <Button
              color="success"
              type="submit"
              value="Submit"
              onClick={this.handleSubmit}
            >
              <i className="fa fa-check" /> Submit
            </Button>
          </CardFooter>
        </Card>
      </div>
    );
  }
}
