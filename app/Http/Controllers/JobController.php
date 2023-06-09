<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use App\Traits\ListingApiTrait;

class JobController extends Controller
{

    use ListingApiTrait;
    public function create(Request $request){
        $request->validate([
            'job_title'      => 'required|string|unique:jobs,job_title',
            'salary'         => 'required|numeric',
            'description'    => 'string|max:250|nullable'
        ]);

        $job = Job::create($request->only('job_title','salary','description'));
        return success('Job Created Successfully',$job);
    }

    public function update(Request $request,Job $id){
        $request->validate([
            'job_title'      => 'string|unique:jobs,job_title,'.$id.',id',
            'salary'         => 'numeric',
            'description'    => 'string|max:250|nullable'
        ]);
        if($id){
            $id->update($request->only('job_title','salary','description'));
            return success('Your Job Is Updated SuccessFully',$id);
        }
        return error('Your Job Is Not Updated',type:'notfound');
    }
    
    public function view(Request $request){
            $this->ListingValidation();
            $query = Job::query();
    
            $searchable_fields = ['job_title','description'];
            $data = $this->filterSearchPagination($query,$searchable_fields);
    
            return success('Job List',[
                'Job'       => $data['query']->get(),
                'count'     => $data['count']
            ]);
    }

    public function delete($id){
        $job = Job::find($id);
        if($job){
            $job->delete();
            return success('Job Deleted Successfully');
        }
        return error(type:'notfound');
    }

    public function get($id){
        $job = Job::find($id);
        if($job){
            return success('Job Data',$job);
        }
        return error('No Data Found');
    }
    

    
}
