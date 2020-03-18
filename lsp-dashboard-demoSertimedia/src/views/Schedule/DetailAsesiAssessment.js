import React, { Component } from "react";
import { Button, Card, CardHeader, CardBody, CardFooter } from "reactstrap";
import { Input, Icon, Table } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import Axios from "axios";
import { Link } from "react-router-dom";
import {
  path_assessments,
  baseUrl,
  getData,
  path_applicant,
  getRole
} from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import { multiLanguage } from "../../components/Language/getBahasa";

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";

const Search = Input.Search;

function deleteUsers(value, assessment_applicant_id) {
  const path = `${path_assessments}/${value}${path_applicant}/${assessment_applicant_id}`;
  Axios(getData(path, "DELETE")).then(response => {
    if (response) {
      window.location.reload();
    }
  });
}

export default class DetailAsesiAssessment extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      pagination: {},
      loading: false,
      offset: 0,
      filteredInfo: null,
      searchText: "",
      name: "",
      assessment_id: "",
      hidden: true,
      message: "",
      payload: []
    };
  }

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

  handleSearch = searchText => {
    const { assessment_id } = this.props.match.params;
    var url =
      baseUrl +
      path_assessments +
      "/" +
      assessment_id +
      path_applicant +
      "?search=" +
      searchText;
    this.setState({ loading: true });
    const auth = Digest(
      path_assessments + "/" + assessment_id + path_applicant,
      "GET"
    );
    reqwest({
      url: url,
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
      this.get();
    }
  };

  handleReset = clearFilters => {
    clearFilters();
    this.setState({ searchText: "" });
  };

  get = (params = {}) => {
    const { assessment_id, assessor_id } = this.props.match.params;
    this.setState({ loading: true });
    const auth = Digest(
      path_assessments + "/" + assessment_id + path_applicant,
      "GET"
    );
    reqwest({
      url: baseUrl + path_assessments + "/" + assessment_id + path_applicant,
      method: "GET",
      data: {
        limit: 10,
        assessor_id: assessor_id,
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
        const pagination = { ...this.state.pagination };
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

  componentDidMount() {
    const { assessment_id } = this.props.match.params;
    this.get();
    Axios(getData(path_assessments + "/" + assessment_id, "GET")).then(
      response => {
        this.setState({
          payload: response.data.data
        });
      }
    );
  }

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
    this.get({
      limit: pagination.pageSize,
      offset: offset,
      sort: sorting
      // sortOrder: sorter.order,
      // ...filters
    });
  };

  handleClick(assessment_id) {
    window.location.assign(`${path_assessments}/${assessment_id}/assign`);
  }

  render() {
    const { assessment_id } = this.props.match.params;
    const { title } = this.state.payload;
    const columns = [
      {
        key: "first_name",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.name}
          </h5>
        ),
        dataIndex: "first_name",
        render: (value, row) => {
          const last_name = row.last_name !== "Undefined" ? row.last_name : "";
          const full = row.first_name + " " + last_name;
          return <div>{row.applicant_id !== "0" ? full : row.full_name}</div>;
        }
      },
      {
        key: "contact",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.contact}
          </h5>
        ),
        dataIndex: "contact",
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "schema_label",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.schema} {multiLanguage.asesi}
          </h5>
        ),
        dataIndex: "schema_label",
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "test_method",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.method_test} {multiLanguage.asesi}
          </h5>
        ),
        dataIndex: "test_method",
        render: value => {
          if (value === null) {
            return "NONE";
          } else if (value === "competency") {
            return <div>{multiLanguage.competencyTest}</div>;
          } else if (value === "portfolio") {
            return <div>{multiLanguage.portfollioTest}</div>;
          } else {
            return <div>{value}</div>;
          }
        }
      },
      {
        key: "status_recomendation",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.recomentAsesor}
          </h5>
        ),
        dataIndex: "status_recomendation",
        render: value => {
          if (value === "K") {
            return <div>Rekomendasi</div>;
          } else if (value === "BK") {
            return <div>Belum Direkomendasikan</div>;
          } else {
            return <div>{value}</div>;
          }
        }
      },
      {
        key: "status_graduation",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.statusGraduation}
          </h5>
        ),
        dataIndex: "status_graduation",
        render: value => {
          if (value === "L") {
            return <div>Kompeten</div>;
          } else if (value === "TL") {
            return <div>Belum Kompeten</div>;
          } else {
            return <div>NONE</div>;
          }
        }
      },
      {
        key: "assessment_id",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.action}
          </h5>
        ),
        dataIndex: "assessment_id",
        // width: '6%',
        render: (value, row) => {
          return (
            <div>
              <Link
                to={`${path_assessments}/${value}${path_applicant}/${row.assessment_applicant_id}/portofolio`}
              >
                <Button className="btn btn-success">
                  {multiLanguage.documentAsesi}
                </Button>
              </Link>{" "}
              <Button
                className="btn btn-danger delete-button col-md-auto"
                title={multiLanguage.delete}
                onClick={function() {
                  if (window.confirm(`${multiLanguage.alertDelete} ?`)) {
                    deleteUsers(value, row.assessment_applicant_id);
                  } else {
                    return;
                  }
                }}
              >
                <i className="fa fa-trash" />
              </Button>
            </div>
          );
        }
      }
    ];
    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader>{`Detail ${multiLanguage.Assessment} ${title}`}</CardHeader>
          <CardBody>
            {`${multiLanguage.searching} `}
            <Search
              placeholder={multiLanguage.search}
              onSearch={this.handleSearch}
              onChange={this.handleChange}
              style={{ width: 310 }}
            />{" "}
            <p />
            <Table
              rowKey={record => record.assessment_applicant_id}
              columns={columns}
              dataSource={this.state.data}
              pagination={this.state.pagination}
              loading={this.state.loading}
              onChange={this.handleTableChange}
              stripe
            />
          </CardBody>
          <CardFooter>
            {getRole() !== "ACS" ? (
              <Button
                className="btn-danger"
                type="submit"
                size="md"
                onClick={this.handleClick.bind(this, assessment_id)}
              >
                <i className="fa fa-chevron-left" /> {multiLanguage.back}
              </Button>
            ) : (
              <Link to={path_assessments}>
                <Button className="btn-danger" type="submit" size="md">
                  <i className="fa fa-chevron-left" /> {multiLanguage.back}
                </Button>
              </Link>
            )}
          </CardFooter>
        </Card>
      </div>
    );
  }
}
