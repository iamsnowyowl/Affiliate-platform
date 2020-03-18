import React, { Component } from "react";
import {
  Card,
  Col,
  CardFooter,
  Button,
  CardHeader,
  CardBody,
  Row,
  Form,
  FormGroup,
  Input,
  Label
} from "reactstrap";
import { Modal } from "antd";
import "../../css/dataRecord.css";
import {
  path_assessments,
  baseUrl,
  path_letters,
  formatCapitalize,
  clearUnderscore
} from "../../components/config/config";
// import Sertificate from './Sertificate';
import { multiLanguage } from "../../components/Language/getBahasa";
// import APL from './APL';
import "../../css/Button.css";
import "../../css/loaderDataTable.css";
import TableList from "../../components/ListTables/TableList";
import { Digest } from "../../containers/Helpers/digest";
import Axios from "axios";
import LoadingOverlay from "react-loading-overlay";
import PDFViewer from "../../components/PDFViewer/PDFViewer";
import PDFJs from "../../backends/pdfjs";

export default class Letters extends Component {
  constructor(props) {
    super(props);
    this.state = {
      open: false,
      assessmentId: "",
      assessmentLetterId: "",
      letter_number: "",
      NoSurat: false,
      loading: false,
      modalDocumentView: false
    };
  }

  onOpenModal = record => {
    console.log(record);
    this.setState({
      assessmentId: record.assessment_id,
      assessmentLetterId: record.assessment_letter_id,
      open: true
    });
  };

  handleChange = event => {
    this.setState({ [event.target.name]: event.target.value });
  };

  handleClick() {
    window.location.assign(`${path_assessments}/list`);
  }

  onOK = event => {
    event.preventDefault();
    this.setState({
      NoSurat: true,
      loading: true,
      open: false
    });
    const authentication = Digest(
      path_assessments +
        "/" +
        this.state.assessmentId +
        path_letters +
        "/" +
        this.state.assessmentLetterId,
      "PUT"
    );

    var data = {};
    data["letter_number"] = this.state.letter_number;

    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        "X-Lsp-Date": authentication.date
      },
      url:
        baseUrl +
        path_assessments +
        "/" +
        this.state.assessmentId +
        path_letters +
        "/" +
        this.state.assessmentLetterId,
      data: data
    };

    Axios(options).then(res => {
      this.setState({
        loading: false
      });
      this.success();
    });
  };

  success = () => {
    Modal.success({
      title: "SUCCESS",
      content: "Berhasil Menambahkan No Surat,silahkan cek Surat Kembali",
      okButtonProps: this.Cancel
    });
  };

  onClose = () => {
    this.setState({
      open: false
    });
  };

  Close = () => {
    window.location.reload();
  };

  toggleDocumentView = record => {
    var url = record.url.replace("/edit", "/preview");
    if (url !== "") {
      Modal.info({
        title: record.assessment_letter_name,
        content: <PDFViewer backend={PDFJs} src={url} />,
        width: 750
      });
    } else {
      Modal.warning({
        title: "Surat Tidak Tergenerate",
        content:
          "Mohon maaf, terjadi Kesalahan Saat mengenerate surat,silahkan coba beberapa saat lagi"
      });
    }
  };

  render() {
    const columns = [
      {
        key: "assessment_letter_name",
        title: multiLanguage.assessmentLetter,
        dataIndex: "assessment_letter_name",
        sorter: true
      },
      {
        key: "letter_type",
        title: multiLanguage.type,
        dataIndex: "letter_type",
        sorter: true,
        render: text => {
          const str = formatCapitalize(text);

          return clearUnderscore(str);
        }
      },
      {
        key: "assessment_letter_id",
        title: multiLanguage.action,
        dataIndex: "assessment_letter_id",
        render: (text, record) => {
          console.log(record);
          var print = (
            <Button
              onClick={() => this.toggleDocumentView(record)}
              color="primary"
            >
              <i class="fa fa-file-pdf-o"></i>
            </Button>
          );

          var baps = (
            <a href={record.url} className="btn btn-primary" target="_blank">
              <i class="fa fa-file-pdf-o"></i>
            </a>
          );

          return record.letter_type === "SURAT_PERMOHONAN_ASSESSMENT" ? (
            print
          ) : record.letter_type === "BAPS" ? (
            <div>{baps}</div>
          ) : (
            <div>{print}</div>
          );
        }
      }
    ];
    const { assessment_id } = this.props.match.params;

    return (
      <div className="animated fadeIn">
        <LoadingOverlay active={this.state.loading} spinner text="Loading..">
          <Card>
            <Modal
              title="Input No Surat"
              visible={this.state.open}
              onCancel={this.onClose}
              onOk={this.onOK}
            >
              <Form
                action=""
                encType="multipart/form-data"
                className="form-horizontal"
              >
                <FormGroup row>
                  <Col md="1.5">
                    <Label htmlFor="no_surat">No Surat</Label>
                  </Col>
                  <Col xs="12" md="5">
                    <Input
                      type="text"
                      style={{ textTranform: "uppercase" }}
                      id="letter_number"
                      name="letter_number"
                      placeholder="000/LSPE/ST/X/2019"
                      onChange={this.handleChange}
                    />
                  </Col>
                </FormGroup>
              </Form>
            </Modal>
            <CardHeader>
              <Row>
                <Col md="6">
                  <h5
                    style={{
                      textDecoration: "underline",
                      color: "navy"
                    }}
                  >
                    {multiLanguage.assessmentLetter}
                  </h5>
                </Col>
              </Row>
            </CardHeader>
            <CardBody>
              <TableList
                columns={columns}
                urls={
                  baseUrl +
                  path_assessments +
                  "/" +
                  assessment_id +
                  path_letters
                }
                path={path_assessments + "/" + assessment_id + path_letters}
                rowKeys={record => record.assessment_letter_id}
              />
            </CardBody>
            <CardFooter>
              <Button
                onClick={this.handleClick.bind(this)}
                className="btn-danger"
                type="submit"
                size="md"
              >
                <i className="fa fa-chevron-left" />
                {` ${multiLanguage.back}`}
              </Button>
            </CardFooter>
          </Card>
        </LoadingOverlay>
      </div>
    );
  }
}
