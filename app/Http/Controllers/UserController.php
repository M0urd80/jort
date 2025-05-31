namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Keyword;

class UserController extends Controller
{
    public function profile()
    {
        return response()->json(Auth::user());
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $user->update($request->only(['name', 'email']));
        return response()->json(['message' => 'Profile updated']);
    }

    public function storeKeyword(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->keywords()->create(['word' => $request->keyword]);

        return response()->json(['message' => 'Keyword added']);
    }
}

