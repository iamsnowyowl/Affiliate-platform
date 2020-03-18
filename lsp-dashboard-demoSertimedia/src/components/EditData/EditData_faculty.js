import React, { Component } from 'react';
import { Redirect } from 'react-router-dom';
import axios from 'axios';
import {
  Button,
  Card,
  CardBody,
  CardFooter,
  CardHeader,
  Col,
  Form,
  FormGroup,
  Input,
  Label,
  Alert
} from 'reactstrap';
import { Digest } from '../../containers/Helpers/digest';
import { Link } from 'react-router-dom';
import { baseUrl, path_faculty } from '../../components/config/config';

class EditData_Faculty extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: {
        faculty_code: '',
        faculty_name: ''
      },
      editFaculty: false,
      message: '',
      hidden: true,
      payload: []
    };
    this.handleChange = this.handleChange.bind(this);
    this.handleClick = this.handleClick.bind(this);
  }

  componentDidMount() {
    // definition value props
    const faculty_code = this.props.match.params.faculty_code;
    const authentication = Digest(path_faculty + '/' + faculty_code, 'GET');
    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        'X-Lsp-Date': authentication.date
      },
      url: baseUrl + path_faculty + '/' + faculty_code
    };
    axios(options).then(request => {
      this.setState({
        payload: request.data.data
      });
    });
  }

  handleChange = event => {
    this.setState({ [event.target.name]: event.target.value });
  };

  handleClick = event => {
    event.preventDefault();

    const faculty_code = this.props.match.params.faculty_code;
    const authentication = Digest(path_faculty + '/' + faculty_code, 'PUT');
    var data = {};

    if (typeof this.state.faculty_code !== 'undefined')
      data['faculty_code'] = this.state.faculty_code;
    if (typeof this.state.faculty_name !== 'undefined')
      data['faculty_name'] = this.state.faculty_name;

    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        'X-Lsp-Date': authentication.date
      },
      url: baseUrl + path_faculty + '/' + faculty_code,
      data: data
    };
    axios(options)
      .then(response => {
        if (response.data.responseStatus === 'SUCCESS') {
          this.setState({ editFaculty: true });
        }
      })
      .catch(error => {
        let responseJSON = error.response;
        if (responseJSON.data.responseStatus === 'ERROR') {
          this.setState({ hidden: false, message: 'Not change' });
        }
      });
  };

  render() {
    const { faculty_code, faculty_name } = this.state.payload;
    if (this.state.editFaculty) {
      return <Redirect to={'/competence/faculty'} />;
    }
    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader>Edit Data</CardHeader>
          <CardBody>
            <Form
              action=""
              encType="multipart/form-data"
              className="form-horizontal"
            >
              <FormGroup row>
                <Col md="3">
                  <Label htmlFor="faculty_code">Faculty Code</Label>
                </Col>
                <Col xs="12" md="9">
                  <Input
                    type="text"
                    id="faculty_code"
                    name="faculty_code"
                    placeholder="Faculty Code"
                    defaultValue={faculty_code}
                    onChange={this.handleChange}
                  />
                </Col>
              </FormGroup>
              <FormGroup row>
                <Col md="3">
                  <Label htmlFor="faculty_name">Faculty Name</Label>
                </Col>
                <Col xs="12" md="9">
                  <Input
                    type="text"
                    id="faculty_name"
                    name="faculty_name"
                    placeholder="Faculty Name"
                    defaultValue={faculty_name}
                    onChange={this.handleChange}
                  />
                </Col>
              </FormGroup>
            </Form>
          </CardBody>
          <CardFooter>
            <Button
              type="submit"
              size="lg"
              color="success"
              onClick={this.handleClick}
            >
              <i className="fa fa-save" /> Save Change
            </Button>{' '}
            <Link to={'/competence/faculty'}>
              <Button type="submit" size="lg" color="danger">
                <i className="fa fa-ban" /> Cancel
              </Button>
              <p />
              <Alert
                className="text-center"
                color="danger"
                hidden={this.state.hidden}
              >
                {this.state.message}
              </Alert>
            </Link>
          </CardFooter>
        </Card>
      </div>
    );
  }
}

export default EditData_Faculty;
