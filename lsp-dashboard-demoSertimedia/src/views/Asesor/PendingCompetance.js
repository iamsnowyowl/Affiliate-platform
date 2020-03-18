import React, { Component } from "react";
import { Link } from "react-router-dom";
import {
  Card,
  CardHeader,
  CardBody,
  CardFooter,
  Button,
  Label,
  Row,
  Col
} from "reactstrap";
import { Modal, Table, Input, Icon, Popconfirm } from "antd";
import ImageZoom from "react-medium-image-zoom";
import reqwest from "reqwest";
import Highlighter from "react-highlight-words";

import {
  baseUrl,
  path_accessorCompetence,
  getData
} from "../../components/config/config";
import { multiLanguage } from "../../components/Language/getBahasa";

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";
import moment from "moment";
import Axios from "axios";
import { Digest } from "../../containers/Helpers/digest";

const Search = Input.Search;

class PendingCompetance extends Component {
  constructor(props) {
    super(props);
    this.state = {
      open: false,
      data: [],
      list_skill: false,
      pagination: {},
      loading: false,
      payload: [],
      status: ""
    };
  }

  componentDidMount() {
    this.fetch();
  }

  fetch = (params = { verification_flag: 0 }) => {
    this.setState({
      loading: true
    });
    const auth = Digest(path_accessorCompetence, "GET");
    reqwest({
      url: baseUrl + path_accessorCompetence,
      method: "GET",
      data: {
        limit: 10,
        ...params
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    })
      .then(response => {
        const pagination = {
          ...this.state.pagination
        };
        const count = parseInt(response.count, 10);
        pagination.total = count;
        this.setState({
          loading: false,
          data: response.data,
          pagination
        });
      })
      .catch(error => {
        if (error.status === 401) {
          localStorage.clear();
          window.location.replace("/login");
        }
      });
  };

  handleTableChange = (pagination, filters, sorter) => {
    const pager = { ...this.state.pagination };
    pager.current = pagination.current;
    this.setState({
      pagination: pager
    });
    const offset = (pagination.current - 1) * pagination.pageSize;

    let sorting = "";
    switch (sorter.order) {
      case "ascend":
        sorting = sorter.field;
        break;

      case "descend":
        sorting = "-" + sorter.field;
        break;

      default:
        break;
    }
    this.fetch({
      limit: pagination.pageSize,
      offset: offset,
      sort: sorting
      // ...filters
    });
  };

  handleSearch = searchText => {
    this.setState({ loading: true });
    const auth = Digest(path_accessorCompetence, "GET");
    reqwest({
      url: baseUrl + path_accessorCompetence + "?search=" + searchText,
      method: "GET",
      data: {
        limit: 10
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    }).then(response => {
      const pagination = { ...this.state.pagination };
      const count = parseInt(response.count, 10);
      pagination.total = count;
      this.setState({
        loading: false,
        data: response.data,
        pagination
      });
    });
  };

  handleChange = event => {
    if (event.target.value === "") {
      this.fetch();
    }
  };

  getColumnSearchProps = dataIndex => ({
    filterDropDown: ({
      setSelectedKeys,
      selectedKeys,
      confirm,
      clearFilters
    }) => (
      <div style={{ padding: 8 }}>
        <Input
          ref={node => {
            this.searcInput = node;
          }}
          placeholder={`Search ${dataIndex}`}
          value={selectedKeys[0]}
          onChange={e =>
            setSelectedKeys(e.target.velue ? [e.target.value] : [])
          }
          onPressEnter={() => this.handleSearch(selectedKeys, confirm)}
          style={{ width: 188, marginBottom: 8, display: "block" }}
        />
        <Button
          type="primary"
          onClick={() => this.handleSearch(selectedKeys, confirm)}
          icon="search"
          size="small"
          style={{ width: 90, marginRight: 8 }}
        >
          {multiLanguage.search}
        </Button>
        <Button
          onClick={() => this.handleReset(clearFilters)}
          size="small"
          style={{ width: 90 }}
        >
          {multiLanguage.reset}
        </Button>
      </div>
    ),
    filterIcon: filtered => (
      <Icon type="search" style={{ color: filtered ? "#1890ff" : undefined }} />
    ),
    onFilter: (value, record) =>
      record[dataIndex]
        .toString()
        .toLowerCase()
        .includes(value.toLowerCase()),
    onFilterDropdownVisibleChange: visible => {
      if (visible) {
        setTimeout(() => this.searcInput.select());
      }
    },
    render: text => (
      <Highlighter
        highlightStyle={{ backgroundColor: "#ffc069", padding: 0 }}
        searchWords={[this.state.searchText]}
        autoEscape
        textToHighlight={text.toString()}
      />
    )
  });

  handleChange = event => {
    this.setState({ [event.target.name]: event.target.value });
  };

  handleClick = event => {
    event.preventDefault();

    const expired_date = this.state.expired_date;
    const formatDate = moment(expired_date).format("YYYY-MM-DD h:mm:ss");

    const accessor_competence_id = this.state.accessor_competence_id;

    var data = {};

    if (typeof formatDate !== "undefined") data["expired_date"] = formatDate;

    Axios(
      getData(
        path_accessorCompetence + "/" + accessor_competence_id,
        "PUT",
        data
      )
    )
      .then(response => {
        if (response.data.responseStatus === "SUCCESS") {
          this.setState({ open: false });
          this.fetch();
        }
      })
      .catch(error => {
        let responseJSON = error.response;
        if (responseJSON.data.responseStatus === "ERROR") {
        }
      });
  };

  onOpenModal = accessor_competence_id => {
    this.setState({ open: true, accessor_competence_id });
    Axios(
      getData(path_accessorCompetence + "/" + accessor_competence_id, "GET")
    );
  };

  onClose = () => {
    this.setState({
      open: false
    });
  };

  deletedCompetence = row => {
    this.setState({
      loading: true
    });
    const auth = Digest(path_accessorCompetence + "/" + row, "DELETE");
    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date,
        "Content-Type": "multipart/form-data"
      },
      url: baseUrl + path_accessorCompetence + "/" + row,
      data: null
    };
    Axios(options).then(res => {
      console.log(res.data);
      window.location.reload();
    });
  };

  render() {
    const columns = [
      {
        key: "first_name",
        dataIndex: "first_name",
        title: (
          <h5 style={{ fontWeight: "bold" }}>{multiLanguage.assessors}</h5>
        ),
        sorter: true,
        render: (value, row) => {
          return value + " " + row.last_name;
        },
        width: "20%"
      },
      {
        key: "sub_schema_name",
        dataIndex: "sub_schema_name",
        title: (
          <h5 style={{ fontWeight: "bold" }}>
            {multiLanguage.competencePending}
          </h5>
        ),
        sorter: true
      },
      {
        key: "certificate_file",
        dataIndex: "certificate_file",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.file}</h5>,
        render: value => {
          return (
            <ImageZoom
              image={{
                src: baseUrl + value,
                alt: "",
                className: "img",
                style: { width: "5em" }
              }}
              zoomImage={{
                src: baseUrl + value,
                alt: ""
              }}
            />
          );
        },
        width: "15%"
      },
      {
        key: "verification_flag",
        dataIndex: "verification_flag",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.action}</h5>,
        width: "20%",
        render: (value, row) => {
          return (
            <div>
              <Button
                className="outline"
                onClick={() => {
                  this.onOpenModal(row.accessor_competence_id);
                }}
                color="info"
              >
                {multiLanguage.confirm}
              </Button>{" "}
              <Popconfirm
                title={multiLanguage.confirmDelete}
                onConfirm={this.deletedCompetence.bind(
                  this,
                  row.accessor_competence_id
                )}
                onCancel={this.cancel}
                okText={multiLanguage.yes}
                cancelText={multiLanguage.no}
              >
                <button className="btn btn-danger" title={multiLanguage.delete}>
                  {multiLanguage.delete}
                </button>
              </Popconfirm>
            </div>
          );
        }
      }
    ];

    return (
      <div className="animated fadeIn">
        <Modal
          visible={this.state.open}
          onCancel={this.onClose}
          onOk={this.handleClick}
        >
          <h2>Form {multiLanguage.activation}</h2>
          <Label>
            {multiLanguage.alertexpired}.
            <p />
            {multiLanguage.noteListSkill}
          </Label>
          <p />
          <input
            type="date"
            id="expired_date"
            onChange={this.handleChange}
            name="expired_date"
            required
          />
          <p />
        </Modal>

        <Card>
          <CardHeader>
            <h5
              style={{
                textDecoration: "underline",
                color: "navy"
              }}
            >
              {multiLanguage.competencePending}
            </h5>
          </CardHeader>
          <CardBody>
            <Row style={{ marginBottom: "12px" }}>
              <Col>
                {`${multiLanguage.searching} `}
                <Search
                  placeholder={multiLanguage.search}
                  onSearch={this.handleSearch}
                  onChange={this.handleChange}
                  style={{ width: 301 }}
                />{" "}
              </Col>
            </Row>
            <Row>
              <Col>
                <Table
                  columns={columns}
                  rowKey="uid"
                  dataSource={this.state.data}
                  pagination={this.state.pagination}
                  loading={this.state.loading}
                  onChange={this.handleTableChange}
                  striped
                />
              </Col>
            </Row>
          </CardBody>
          <CardFooter>
            <Link to={`/Assessors`}>
              <Button className="outline" color="danger">
                <i className="fa fa-chevron-left chevron-left" />
                {` ${multiLanguage.back}`}
              </Button>
            </Link>
          </CardFooter>
        </Card>
      </div>
    );
  }
}

export default PendingCompetance;
