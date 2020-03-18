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
  Alert,
  Row
} from 'reactstrap';
import { Digest } from '../../containers/Helpers/digest';
import { Link } from 'react-router-dom';
import { baseUrl, path_schema, formatCapitalize } from '../config/config';
import { multiLanguage } from '../Language/getBahasa';

class EditData_mainSchema extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: {
        skkni: '',
        skkni_year: '',
        schema_name: '',
        total_uk: ''
      },
      editFaculty: false,
      message: '',
      hidden: true,
      bahasa: '',
      payload: []
    };
  }

  componentWillMount() {
    const schema_id = this.props.match.params.schema_id;
    const authentication = Digest(path_schema + '/' + schema_id, 'GET');
    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        'X-Lsp-Date': authentication.date
      },
      url: baseUrl + path_schema + '/' + schema_id
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
    const schema_id = this.props.match.params.schema_id;
    const authentication = Digest(path_schema + '/' + schema_id, 'PUT');
    var data = {};
    data['skkni'] = formatCapitalize(this.state.skkni);
    data['skkni_year'] = formatCapitalize(this.state.skkni_year);
    data['schema_name'] = formatCapitalize(this.state.schema_name);
    data['total_uk'] = formatCapitalize(this.state.total_uk);
    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        'X-Lsp-Date': authentication.date
      },
      url: baseUrl + path_schema + '/' + schema_id,
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
    const { skkni, skkni_year, schema_name, total_uk } = this.state.payload;
    if (this.state.editFaculty) {
      return <Redirect to={'/schema/main-schema'} />;
    }
    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader
            style={{ textAlign: 'center' }}
          >{`${multiLanguage.Edit} Data`}</CardHeader>
          <CardBody>
            <Form
              action=""
              encType="multipart/form-data"
              className="form-horizontal"
            >
              <FormGroup row>
                <Col md="2">
                  <Label htmlFor="skkni">SKKNI Atau SKKK / Thn</Label>
                </Col>
                <Col xs="4" md="2">
                  <Input
                    type="text"
                    style={{ borderColor: 'black' }}
                    id="skkni"
                    name="skkni"
                    placeholder="SKKNI"
                    defaultValue={skkni === 'undefined' ? '' : skkni}
                    onChange={this.handleChange}
                  />
                </Col>
                <Col md="0">/</Col>
                <Col xs="4" md="2">
                  <Input
                    type="text"
                    style={{ borderColor: 'black' }}
                    id="skkni_year"
                    name="skkni_year"
                    placeholder="Thn"
                    defaultValue={skkni_year}
                    onChange={this.handleChange}
                  />
                </Col>
              </FormGroup>
              <FormGroup row>
                <Col md="2">
                  <Label htmlFor="schema_name">
                    {multiLanguage.schemaName}
                  </Label>
                </Col>
                <Col xs="12" md="4">
                  <Input
                    type="text"
                    style={{ borderColor: 'black' }}
                    id="schema_name"
                    name="schema_name"
                    placeholder="Faculty Name"
                    defaultValue={schema_name}
                    onChange={this.handleChange}
                  />
                </Col>
                <Col md="2">
                  <Label htmlFor="total_uk">{multiLanguage.totalUnit}</Label>
                </Col>
                <Col xs="12" md="2">
                  <Input
                    type="number"
                    style={{ borderColor: 'black' }}
                    id="total_uk"
                    name="total_uk"
                    placeholder="0"
                    defaultValue={total_uk}
                    onChange={this.handleChange}
                  />
                </Col>
              </FormGroup>
            </Form>
            <Alert
              className="text-center"
              color="danger"
              hidden={this.state.hidden}
            >
              {this.state.message}
            </Alert>
          </CardBody>
          <CardFooter>
            <Row>
              <Col md="1.5">
                <Link to={'/schema/main-schema'}>
                  <Button type="submit" size="md" color="danger">
                    <i className="fa fa-close" /> {multiLanguage.cancel}
                  </Button>
                </Link>
              </Col>
              <Col md="1.5" className="Btn-Submit">
                <Button
                  type="submit"
                  size="md"
                  color="success"
                  onClick={this.handleClick}
                >
                  <i className="fa fa-save" /> {multiLanguage.submit}
                </Button>
              </Col>
            </Row>
          </CardFooter>
        </Card>
      </div>
    );
  }
}

export default EditData_mainSchema;
